<?php

namespace App\Controller;

use App\Entity\TableKanban;
use App\Form\TableKanbanType;
use App\Repository\TableKanbanRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/table/kanban")
 */
class TableKanbanController extends AbstractController
{
    /**
     * @Route("/", name="table_kanban_index", methods={"GET"})
     */
    public function index(TableKanbanRepository $tableKanbanRepository): Response
    {
        return $this->render('table_kanban/index.html.twig', [
            'table_kanbans' => $tableKanbanRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="table_kanban_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tableKanban = new TableKanban();
        $form = $this->createForm(TableKanbanType::class, $tableKanban);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tableKanban);
            $entityManager->flush();

            return $this->redirectToRoute('table_kanban_index');
        }

        return $this->render('table_kanban/new.html.twig', [
            'table_kanban' => $tableKanban,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="table_kanban_show", methods={"GET"})
     */
    public function show(TableKanban $tableKanban): Response
    {
        return $this->render('table_kanban/show.html.twig', [
            'table_kanban' => $tableKanban,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="table_kanban_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TableKanban $tableKanban): Response
    {
        $form = $this->createForm(TableKanbanType::class, $tableKanban);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('table_kanban_index');
        }

        return $this->render('table_kanban/edit.html.twig', [
            'table_kanban' => $tableKanban,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="table_kanban_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TableKanban $tableKanban): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tableKanban->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tableKanban);
            $entityManager->flush();
        }

        return $this->redirectToRoute('table_kanban_index');
    }
}
