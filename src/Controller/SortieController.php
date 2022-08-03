<?php

namespace App\Controller;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/{id}", name="afficherSortie")
     */
    public function afficherSortie(int $id, SortieRepository $repo): Response
    {
        $sortie = $repo->find($id);

        return $this->render('sortie/afficherSortie.html.twig', [
            "sortie" => $sortie
        ]);
    }
}
