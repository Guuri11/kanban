<?php


namespace App\Controller\Api;


use App\Entity\TableKanban;
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
 * Class TableKanbanController
 * @package App\Controller\Api
 * @Route("/api/table")
 */
class TableKanbanController extends AbstractController
{

    /**
     * @Route("/", name="api_table_retrieve", methods={"GET"})
     * @param Request $request
     * @param TableKanbanRepository $tableKanbanRepository
     * @param UserRepository $userRepository
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function index(Request $request, TableKanbanRepository $tableKanbanRepository, UserRepository $userRepository,
                          ApiUtils $apiUtils) : JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $userRepository->findOneBy(["username"=>$this->getUser()->getUsername()]);
        $results = $tableKanbanRepository->findBy(["user"=>$user->getId()]);

        if (empty($results)) {
            $apiUtils->errorResponse("No has creado ninguna tabla aún");
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND);
        }
        else{
            $apiUtils->successResponse("OK",$results);
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_OK);
        }
    }

    /**
     * @Route("/{id}", name="api_table_show", methods={"GET"})
     * @param TableKanban $tableKanban
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function show(TableKanban $tableKanban, ApiUtils $apiUtils): JsonResponse
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($tableKanban === "" || $tableKanban->getUser()->getUsername() !== $this->getUser()->getUsername()){
            $apiUtils->notFoundResponse("Tabla no encontrada");
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
        }
        $apiUtils->successResponse("OK",$tableKanban);
        return new JsonResponse($apiUtils->getResponse(),Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_table_new", methods={"POST"})
     * @param Request $request
     * @param UserRepository $userRepository
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function new(Request $request, UserRepository $userRepository, ApiUtils $apiUtils): JsonResponse
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $table = new TableKanban();
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
                    $user = $userRepository->findOneBy(["username"=>$this->getUser()->getUsername()]);
                    $table->setName($data["name"]);
                    $table->setUser($user);

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($table);
                    $em->flush();

                } catch (Exception $e){
                    $apiUtils->errorResponse( "No se pudo crear la tabla");
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
                }
                $apiUtils->successResponse("¡Tabla creada!",$table);
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
     * @Route("/edit/{id}", name="api_table_update", methods={"PUT"})
     * @param Request $request
     * @param TableKanban $tableKanban
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function edit(Request $request, TableKanban $tableKanban, ApiUtils $apiUtils): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get request data
        $apiUtils->getContent($request);

        // Sanitize data
        $apiUtils->setData($apiUtils->sanitizeData($apiUtils->getData()));
        $data = $apiUtils->getData();

        if ($tableKanban->getUser()->getUsername() !== $this->getUser()->getUsername()){
            $apiUtils->notFoundResponse("Tabla no encontrada");
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
        }

        // CSRF Protection process
        if (!empty($data["token"])) {
            // if token received is the same than original do process
            if (hash_equals($_SESSION["token"], $data["token"])) {
                try {
                    $tableKanban->setName($data["name"]);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                } catch (Exception $e){
                    $apiUtils->errorResponse("Error en la actualización");
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
                }

                $apiUtils->successResponse("¡Actualización con éxito!", $tableKanban);
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
     * @param TableKanban $tableKanban
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function delete(Request $request, TableKanban $tableKanban, ApiUtils $apiUtils): JsonResponse
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
                    if ($tableKanban === "" || $tableKanban->getUser()->getUsername() !== $this->getUser()->getUsername()){
                        $apiUtils->notFoundResponse("Tabla no encontrada");
                        return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
                    }
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($tableKanban);
                    $entityManager->flush();
                }catch (Exception $e) {
                    $apiUtils->errorResponse("No se pudo borrar la tabla");
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_ACCEPTED,['Content-type'=>'application/json']);
                }

                $apiUtils->successResponse("¡Tabla borrada!");
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