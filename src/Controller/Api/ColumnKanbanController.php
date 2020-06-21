<?php


namespace App\Controller\Api;

use App\Entity\ColumnKanban;
use App\Repository\ColumnKanbanRepository;
use App\Repository\TableKanbanRepository;
use App\Repository\UserRepository;
use App\Utils\ApiUtils;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ColumnKanbanController
 * @package App\Controller\Api
 * @Route("/api/column")
 */
class ColumnKanbanController extends AbstractController
{
    /**
     * @Route("/", name="api_column_retrieve", methods={"GET"})
     * @param Request $request
     * @param ColumnKanbanRepository $columnKanbanRepository
     * @param TableKanbanRepository $tableKanbanRepository
     * @param UserRepository $userRepository
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function index(Request $request, ColumnKanbanRepository $columnKanbanRepository, TableKanbanRepository $tableKanbanRepository,
                          UserRepository $userRepository, ApiUtils $apiUtils) : JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get params
        $apiUtils->getRequestParams($request);

        // Sanitize data
        $apiUtils->setParameters($apiUtils->sanitizeData($apiUtils->getParameters()));

        $data = $apiUtils->getParameters();

        // Get table a check if user is who requests column
        $table = $tableKanbanRepository->find(intval($data["id"]));

        if (empty($table) || $table->getUser()->getUsername() !== $this->getUser()->getUsername()){
            $apiUtils->errorResponse("No existe la table");
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND);
        }

        $results = $columnKanbanRepository->findBy(["table_kanban_id"=>$table->getId()]);

        if (empty($results)) {
            $apiUtils->errorResponse("Aún no has desarrollado tu tabla Kanban");
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND);
        }
        else{
            $apiUtils->successResponse("OK",$results);
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_OK);
        }
    }

    /**
     * @Route("/new", name="api_column_new", methods={"POST"})
     * @param Request $request
     * @param TableKanbanRepository $tableKanbanRepository
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function new(Request $request, TableKanbanRepository $tableKanbanRepository, ApiUtils $apiUtils): JsonResponse
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $column = new ColumnKanban();
        // Get request data
        $apiUtils->getContent($request);

        // Sanitize data
        $apiUtils->setData($apiUtils->sanitizeData($apiUtils->getData()));
        $data = $apiUtils->getData();

        // CSRF Protection process
        if (!empty($data["token"])) {
            // if token received is the same than original do process
            if (hash_equals($_SESSION["token"], $data["token"])) {
                try {
                    $column->setName($data["name"]);

                    $table = $tableKanbanRepository->find(intval($data["table"]));
                    $column->setTableKanban($table);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($column);
                    $em->flush();

                } catch (Exception $e){
                    $apiUtils->errorResponse( "No se pudo crear la columna");
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
                }
                $apiUtils->successResponse("¡Columna creada!",$column);
                return new JsonResponse($apiUtils->getResponse(), Response::HTTP_CREATED, ['Content-type' => 'application/json']);
            }else {
                // Send error response if csrf token isn't valid
                $apiUtils->setResponse([
                    "success" => false,
                    "message" => "Validación no completada",
                    "errors" => []
                ]);
                return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
            }
        }else {
            // Send error response if there's no csrf token
            $apiUtils->setResponse([
                "success" => false,
                "message" => "Validación no completada",
                "errors" => $data["token"]
            ]);
            return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
        }
    }


    /**
     * @Route("/edit/{id}", name="api_column_update", methods={"PUT"})
     * @param Request $request
     * @param ColumnKanban $columnKanban
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function edit(Request $request, ColumnKanban $columnKanban, ApiUtils $apiUtils): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get request data
        $apiUtils->getContent($request);

        // Sanitize data
        $apiUtils->setData($apiUtils->sanitizeData($apiUtils->getData()));
        $data = $apiUtils->getData();

        if ($columnKanban->getTableKanban()->getUser()->getUsername() !== $this->getUser()->getUsername()){
            $apiUtils->notFoundResponse("Columna no encontrada");
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
        }

        // CSRF Protection process
        if (!empty($data["token"])) {
            // if token received is the same than original do process
            if (hash_equals($_SESSION["token"], $data["token"])) {
                try {
                    $columnKanban->setName($data["name"]);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                } catch (Exception $e){
                    $apiUtils->errorResponse("Error en la actualización");
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
                }

                $apiUtils->successResponse("¡Actualización con éxito!", $columnKanban);
                return new JsonResponse($apiUtils->getResponse(), Response::HTTP_ACCEPTED,['Content-type'=>'application/json']);
            }else {
                // Send error response if csrf token isn't valid
                $apiUtils->setResponse([
                    "success" => false,
                    "message" => "Validación no completada",
                    "errors" => []
                ]);
                return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
            }
        }else {
            // Send error response if there's no csrf token
            $apiUtils->setResponse([
                "success" => false,
                "message" => "Validación no completada",
                "errors" => $data["token"]
            ]);
            return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
        }
    }

    /**
     * @Route("/delete/{id}", name="api_table_delete", methods={"DELETE"})
     * @param Request $request
     * @param ColumnKanban $columnKanban
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function delete(Request $request, ColumnKanban $columnKanban, ApiUtils $apiUtils): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get request data
        $apiUtils->getContent($request);

        // Sanitize data
        $apiUtils->setData($apiUtils->sanitizeData($apiUtils->getData()));
        $data = $apiUtils->getData();

        // CSRF Protection process
        if (!empty($data["token"])) {
            // if token received is the same than original do process
            if (hash_equals($_SESSION["token"], $data["token"])) {
                try {
                    if ($columnKanban === "" || $columnKanban->getTableKanban()->getUser()->getUsername() !== $this->getUser()->getUsername()){
                        $apiUtils->notFoundResponse("Columna no encontrada");
                        return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
                    }
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($columnKanban);
                    $entityManager->flush();
                }catch (Exception $e) {
                    $apiUtils->errorResponse("No se pudo borrar la columna");
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_ACCEPTED,['Content-type'=>'application/json']);
                }

                $apiUtils->successResponse("¡Columna borrada!");
                return new JsonResponse($apiUtils->getResponse(), Response::HTTP_ACCEPTED,['Content-type'=>'application/json']);
            }else {
                // Send error response if csrf token isn't valid
                $apiUtils->setResponse([
                    "success" => false,
                    "message" => "Validación no completada",
                    "errors" => []
                ]);
                return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
            }
        }else {
            // Send error response if there's no csrf token
            $apiUtils->setResponse([
                "success" => false,
                "message" => "Validación no completada",
                "errors" => $data["token"]
            ]);
            return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
        }
    }

}