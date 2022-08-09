<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Sortie;
use App\Form\SearchForm;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main_home")
     */
    public function list(SortieRepository $repo, Request $request){
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
