<?php


namespace App\Controller\Api;

use App\Entity\Task;
use App\Repository\ColumnKanbanRepository;
use App\Repository\TableKanbanRepository;
use App\Repository\TaskRepository;
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
 * @Route("/api/task")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/{id}", name="api_task_show", methods={"GET"})
     * @param ApiUtils $apiUtils
     * @param Task $task
     * @return JsonResponse
     */
    public function show(ApiUtils $apiUtils, Task $task): JsonResponse
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        if ($task === "" || $task->getColumnKanban()->getTableKanban()->getUser()->getUsername() !== $this->getUser()->getUsername()){
            $apiUtils->notFoundResponse("Tarea no encontrada");
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
        }
        $apiUtils->successResponse("OK",$task);
        return new JsonResponse($apiUtils->getResponse(),Response::HTTP_OK);
    }

    /**
     * @Route("/new", name="api_task_new", methods={"POST"})
     * @param Request $request
     * @param ColumnKanbanRepository $columnKanbanRepository
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function new(Request $request, ColumnKanbanRepository $columnKanbanRepository, ApiUtils $apiUtils): JsonResponse
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $task = new Task();
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
                    $task->setName($data["name"]);
                    $task->setDescription("");
                    $task->setFinished(false);
                    $task->setCreatedAt(new \DateTime());

                    $column = $columnKanbanRepository->find(intval($data["column"]));

                    if ($column->getTableKanban()->getUser()->getUsername() !== $this->getUser()->getUsername()){
                        $apiUtils->notFoundResponse("Columna no encontrada");
                        return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
                    }

                    $task->setColumnKanban($column);

                    $task->setPosition(count($task->getColumnKanban()->getTasks()->toArray()));

                    $em = $this->getDoctrine()->getManager();
                    $em->persist($task);
                    $em->flush();

                } catch (Exception $e){
                    $apiUtils->errorResponse( "No se pudo crear la tarea");
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
                }
                $apiUtils->successResponse("¡Tarea creada!",$task);
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
     * @Route("/edit/{id}", name="api_task_update", methods={"PUT"})
     * @param Request $request
     * @param Task $task
     * @param ColumnKanbanRepository $columnKanbanRepository
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function edit(Request $request, Task $task, ColumnKanbanRepository $columnKanbanRepository, ApiUtils $apiUtils): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get request data
        $apiUtils->getContent($request);

        // Sanitize data
        $apiUtils->setData($apiUtils->sanitizeData($apiUtils->getData()));
        $data = $apiUtils->getData();

        if ($task->getColumnKanban()->getTableKanban()->getUser()->getUsername() !== $this->getUser()->getUsername()){
            $apiUtils->notFoundResponse("Tarea no encontrada");
            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
        }

        // CSRF Protection process
        if (!empty($data["token"])) {
            // if token received is the same than original do process
            if (hash_equals($_SESSION["token"], $data["token"])) {
                try {
                    $task->setName($data["name"]);
                    $task->setDescription($data["description"]);
                    $task->setFinished($data["finished"]);

                    $columnKanban = $columnKanbanRepository->find(intval($data["column"]));

                    $task->setColumnKanban($columnKanban);
                    if ($task->getFinished())
                        $task->setFinishedAt(new \DateTime());
                    else
                        $task->setFinishedAt(null);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();
                } catch (Exception $e){
                    $apiUtils->errorResponse("Error en la actualización",[$e->getMessage()]);
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
                }

                $apiUtils->successResponse("¡Actualización con éxito!", $task);
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
     * @Route("/swap", name="api_task_swap", methods={"PUT"})
     * @param Request $request
     * @param TaskRepository $taskRepository
     * @param ColumnKanbanRepository $columnKanbanRepository
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function swapPositions(Request $request, TaskRepository $taskRepository,
                                    ColumnKanbanRepository $columnKanbanRepository, ApiUtils $apiUtils) : JsonResponse
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
                    $task_one = $taskRepository->find(intval($data["task_one"]));

                    // Check task owner
                    if ($task_one->getColumnKanban()->getTableKanban()->getUser()->getUsername() !== $this->getUser()->getUsername()){
                        $apiUtils->notFoundResponse("Tarea no encontrada");
                        return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
                    }

                    if (array_key_exists('task_two', $data)){

                        $task_two = $taskRepository->find(intval($data["task_two"]));

                        // Check tasks owner
                        if ($task_two->getColumnKanban()->getTableKanban()->getUser()->getUsername() !== $this->getUser()->getUsername()){
                            $apiUtils->notFoundResponse("Tarea no encontrada");
                            return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
                        }

                        $arr_tasks = $task_two->getColumnKanban()->getTasks()->toArray();

                        // Swap positions
                        if ($data["change_column"]){
                            $task_one->setColumnKanban($task_two->getColumnKanban());
                            array_splice( $arr_tasks, $task_two->getPosition(), 0, [$task_one] );

                            foreach ($arr_tasks as $key=>$task) {
                                $task->setPosition($key);
                            }

                        } else {
                            $aux = $task_one->getPosition();
                            $task_one->setPosition($task_two->getPosition());
                            $task_two->setPosition($aux);
                        }

                    } else {
                        $arr_tasks = $columnKanbanRepository->find(intval($data['column']))->getTasks()->toArray();
                        $task_one->setColumnKanban( $columnKanbanRepository->find(intval($data['column'])) );
                        array_push($arr_tasks, $task_one);

                        foreach ($arr_tasks as $key=>$task) {
                            $task->setPosition($key);
                        }
                    }

                    // update
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->flush();

                } catch (Exception $e){
                    $apiUtils->errorResponse("Error en la actualización");
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_BAD_REQUEST, ['Content-type' => 'application/json']);
                }

                $apiUtils->successResponse("¡Actualización con éxito!", $task_one);
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
     * @Route("/delete/{id}", name="api_task_delete", methods={"DELETE"})
     * @param Request $request
     * @param Task $task
     * @param ApiUtils $apiUtils
     * @return JsonResponse
     */
    public function delete(Request $request, Task $task, ApiUtils $apiUtils): JsonResponse
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
                    if ($task === "" || $task->getColumnKanban()->getTableKanban()->getUser()->getUsername() !== $this->getUser()->getUsername()){
                        $apiUtils->notFoundResponse("Tarea no encontrada");
                        return new JsonResponse($apiUtils->getResponse(),Response::HTTP_NOT_FOUND,['Content-type'=>'application/json']);
                    }
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($task);
                    $entityManager->flush();
                }catch (Exception $e) {
                    $apiUtils->errorResponse("No se pudo borrar la tarea");
                    return new JsonResponse($apiUtils->getResponse(), Response::HTTP_ACCEPTED,['Content-type'=>'application/json']);
                }

                $apiUtils->successResponse("¡Tarea borrada!");
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