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
     * @Route("/mobile", name="mobile_home")
     */
    public function mesSorties(SortieRepository $repo, MobileDetectorInterface $mobileDetector)
    {
        $sorties = $repo->findAllUserInscrit($this->getUser());

        return $this-> render('mobile/home.html.twig', [
            "sorties" => $sorties
        ]);
    }

    /**
     * @Route("/afficherSortie/{id}", name="afficherSortie")
     */
    public function mobile_afficherSortie(int $id, SortieRepository $repo): Response
        {
            $sortie = $repo->find($id);

            if (!$sortie)
                throw $this->createNotFoundException('Pas de sortie avec cet identifiant');

            return $this->render('mobile/afficherSortieMobile.html.twig', [
                "sortie" => $sortie
            ]);
        }
}
