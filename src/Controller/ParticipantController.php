<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Form\RegistrationFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/afficherProfil", name="afficherProfil")
     */
    public function afficherProfil(int $id, ParticipantRepository $participantRepository): Response
    {
        $participant = $participantRepository->find($id);

        if (!$participant)
            throw $this->createNotFoundException('Pas de participant avec cet identifiant');

        return $this->render('participant/afficherProfil.html.twig', [
            "participant" => $participant
        ]);
    }

    /**
     * @Route("/modifierProfil/{id}", name="modifierProfil")
     */
    public function edit(Request $request, Participant $participant, EntityManagerInterface $entityManager): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if($this->getUser() === $participant) {
            return $this->redirectToRoute('main_home');
        }

        $participantForm = $this->createForm(ParticipantType::class, $participant);
        $participantForm->handleRequest($request);

        if($participantForm->isSubmitted() && $participantForm->isValid()){
            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Informations modifiées avec succès !');
            return $this->redirectToRoute('main_home', ['id' => $participant->getId()]);
        }
        $entityManager->flush();

        return $this->render('participant/modifierProfil.html.twig', [
            'participantForm' => $participantForm->createView()
        ]);
    }
}
