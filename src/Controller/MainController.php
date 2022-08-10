<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SearchForm;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;

use MobileDetectBundle\DeviceDetector\MobileDetectorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MainController extends AbstractController
{
    private MobileDetectorInterface $mobileDetector;

    public function __construct(MobileDetectorInterface $mobileDetector)
    {
        $this->mobileDetector = $mobileDetector;
    }

    /**
     * @Route("/", name="main_home")
     */
    public function list(SortieRepository $repo, Request $request){

//        //Création d'un repo avec les sorties 'Terminées'
//        $sortiesTerminees = $repo ->findBy(array('etat' => '13'));

        /*if ($mobileDetector->isMobile())
            $this->redirectToRoute('mobile_home');*/



        if ($this->mobileDetector -> isMobile())
        {
            $user = $this->getUser();

            $sorties = $repo->findAllUserInscrit($user);

            return $this-> render('main/home.html.twig', [
                "sorties" => $sorties
            ]);
        } else {
            //Récupération de l'utilisateur
            $user = $this->getUser();

            $data = new SearchData();
            $form = $this->createForm(SearchForm::class, $data);

            $form -> handleRequest($request);

            $sorties = $repo->findSearch($data, $user);

            return $this-> render('main/home.html.twig', [
                'sorties' => $sorties,
                'form' => $form -> createView()
            ]);
        }
    }
}

