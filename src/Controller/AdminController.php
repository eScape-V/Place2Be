<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/villes", name="villes")
     */
    public function viles(): Response
    {
        return $this->render('admin/villes.html.twig');
    }

    /**
     * @Route("/campus", name="campus")
     */
    public function campus(): Response
    {
        return $this->render('admin/campus.html.twig');
    }

    /**
     * @Route("/utilisateurs", name="utilisateurs")
     */
    public function utilisateurs(): Response
    {
        return $this->render('admin/utilisateurs.html.twig');
    }
}
