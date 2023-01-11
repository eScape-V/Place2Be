<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Form\RegistrationFormType;
use App\Repository\FileUploader;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/afficherProfil/{id}", name="afficherProfil")
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
    public function modifierProfil(Request $request,
                                   $id,
                                   EntityManagerInterface $entityManager,
                                   UserPasswordHasherInterface $hasher,
                                    FileUploader $fileUploader): Response
    {
        $participant = $entityManager->getRepository(Participant::class)->find($id);

        //Vérification que l'utilisateur est connecté, redirection vers le login si non
        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        if (!$participant) {
            throw $this->createNotFoundException(
                'Pas d\'utilisateur avec l\'identifiant '.$id
            );
        }

        //Vérification que le profil que veut modifier l'utilisateur est bien le sien, sinon redirection Accueil
        //et message flash d'erreur
        if($this->getUser() !== $participant) {
            $this->addFlash('error', 'Accès refusé');
            return $this->redirectToRoute('main_home');
        }

        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            //On vérifie si le mot de passe correspond au plainPassword rentré dans le form, pas au password crypté de la BDD
            if($hasher->isPasswordValid($participant, $form->get('plainPassword')->getData())){
                //Upload de l'image
                $file = $form->get('imageFile')->getData();
                if($file) {
                    $fileName = $fileUploader->upload($file);
                    $participant->setImageName($fileName);
                }

                $entityManager->persist($participant);
                $entityManager->flush();

                $this->addFlash('success', 'Informations modifiées avec succès !');
                return $this->redirectToRoute('main_home', ['id' => $participant->getId()]);
            } else {
                $this->addFlash('warning', 'Le mot de passe est incorrect.');
            }
        }

        //Si le formulaire est valide, enregistrement en BDD, redirection vers l'accueil avec message de succès
        return $this->render('participant/modifierProfil.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
