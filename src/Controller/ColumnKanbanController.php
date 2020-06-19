<?php

namespace App\Controller;

use App\Entity\ColumnKanban;
use App\Form\ColumnKanbanType;
use App\Repository\ColumnKanbanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/column/kanban")
 */
class ColumnKanbanController extends AbstractController
{
    /**
     * @Route("/", name="column_kanban_index", methods={"GET"})
     */
    public function index(ColumnKanbanRepository $columnKanbanRepository): Response
    {
        return $this->render('column_kanban/index.html.twig', [
            'column_kanbans' => $columnKanbanRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="column_kanban_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $columnKanban = new ColumnKanban();
        $form = $this->createForm(ColumnKanbanType::class, $columnKanban);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($columnKanban);
            $entityManager->flush();

            return $this->redirectToRoute('column_kanban_index');
        }

        return $this->render('column_kanban/new.html.twig', [
            'column_kanban' => $columnKanban,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="column_kanban_show", methods={"GET"})
     */
    public function show(ColumnKanban $columnKanban): Response
    {
        return $this->render('column_kanban/show.html.twig', [
            'column_kanban' => $columnKanban,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="column_kanban_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ColumnKanban $columnKanban): Response
    {
        $form = $this->createForm(ColumnKanbanType::class, $columnKanban);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('column_kanban_index');
        }

        return $this->render('column_kanban/edit.html.twig', [
            'column_kanban' => $columnKanban,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="column_kanban_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ColumnKanban $columnKanban): Response
    {
        if ($this->isCsrfTokenValid('delete'.$columnKanban->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($columnKanban);
            $entityManager->flush();
        }

        return $this->redirectToRoute('column_kanban_index');
    }
}
