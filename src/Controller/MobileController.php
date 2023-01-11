<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;



class MobileController extends AbstractController
{
    /**
     * @Route("/afficherSortie/{id}", name="afficherSortie")
     */
    public function mobile_afficherSortie(int $id, SortieRepository $repo): Response
        {
            if(!$this->getUser()) {
                return $this->redirectToRoute('app_login');
            }
            $sortie = $repo->find($id);

            if (!$sortie)
                throw $this->createNotFoundException('Pas de sortie avec cet identifiant');

            return $this->render('mobile/afficherSortieMobile.html.twig', [
                "sortie" => $sortie
            ]);
        }
}
