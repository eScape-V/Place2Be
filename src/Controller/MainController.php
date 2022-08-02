<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
/*
 *
 */

    /**
     * @Route("/", name="main_home")
     */
    public function list(SortieRepository $repo){

        $sorties = $repo->findAll();

        return $this-> render('main/home.html.twig', [
            "sorties" => $sorties
        ]);
    }
    /*
    /**
     * @Route("/", name="main_home")
     *
    *public function home(): Response
    *{
    *    return $this->render('main/home.html.twig');
    *}
    */


    /**
     * @Route("/sortie/", name="sortie_show")
     */
    public function show(SortieRepository $repo): Response
    {
        $sortie = $repo->find(2);

        return $this->render('sortie/show.html.twig', [
        "sortie" => $sortie
        ]);
    }
}
