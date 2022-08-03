<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/details/{id}", name="afficherSortie")
     */
    public function afficherSortie(int $id, SortieRepository $repo): Response
    {
        $sortie = $repo->find($id);

        if (!$sortie)
            throw $this->createNotFoundException('Pas de sortie avec cet identifiant');

        return $this->render('sortie/afficherSortie.html.twig', [
            "sortie" => $sortie
        ]);
    }

    /**
     * @Route("/creer", name="creerSortie")
     */
    public function creerSortie(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        if(!$this->getUser()) {
            $this->addFlash('error', 'Veuillez vous connecter pour créer une sortie');
            return $this->redirectToRoute('app_login');
        }
        $sortie->setOrganisateur($this->getUser());
        $etat = new Etat("1");
        $etat->setLibelle("Créée");
        $sortie->setEtat($etat);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()){
            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie crée avec succès !');

            return $this->redirectToRoute('main_home');

        }
        return $this->render('sortie/creerSortie.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    /**
     * @Route("/inscription/{id}", name="inscriptionSortie")
     */
    public function inscriptionSortie(int $id, EntityManagerInterface $entityManager): Response
    {
        if(!$this->getUser()) {
            $this->addFlash('error', 'Veuillez vous connecter pour vous inscrire à une sortie');
            return $this->redirectToRoute('app_login');
        } else {
            $sortie = $entityManager->getRepository(Sortie::class)->find($id);

            if (!$sortie)
                throw $this->createNotFoundException('Pas de sortie avec cet identifiant');

            if ($sortie->getEtat()->getLibelle() === "Ouverte") {
                $sortie->addParticipant($this->getUser());

                $entityManager->persist($sortie);
                $entityManager->flush();

                $this->addFlash('success', 'Inscription à la sortie validée !');
            } else {
                $this->addFlash('error', 'Impossible de s\'inscrire : vérifiez l\'état de la sortie et la dâte de clôture.');
            }

            return $this->redirectToRoute('main_home');
        }
    }
    //TODO: modifierSortie, desistementSortie
}
