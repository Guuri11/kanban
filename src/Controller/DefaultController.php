<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="default")
     *
     * Route to home page
     */
    public function default()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/{reactRouting}", name="homepage", requirements={"reactRouting"=".+"}, defaults={"reactRouting": null}))
     *
     * Controls all the public routes but dont access into /admin
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }
}
