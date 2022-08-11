<?php

namespace App\Controller;

use App\Repository\GroupeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GroupeController extends AbstractController
{
    /**
     * @Route("/groupe", name="app_groupe")
     */
    public function listGroupes(GroupeRepository $groupeRepository, Request $request):
    {
        $groupes = $groupeRepository->findAll();

        return $this->render('groupe/index.html.twig', [
            'groupes' => $groupes,
            'form' => $form -> createView(),
        ]);
    }


    public function createGroupe(Request $request,
                             EntityManagerInterface $entityManager): Response
    {
        $participant = new Participant();
        //Affectation du ROLE USER par défaut, et Administrateur et Actif sur false par défaut lors de l'inscription
        $participant->setAdministrateur(false);
        $participant->setActif(true);
        $participant->setRoles(["ROLE_USER"]);

        $form = $this->createForm(RegistrationFormType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $participant->setPassword(
                $userPasswordHasher->hashPassword(
                    $participant,
                    $form->get('plainPassword')->getData()
                )
            );
            //Upload de l'image
            $file = $form->get('imageFile')->getData();
            if($file) {
                $fileName = $fileUploader->upload($file);
                $participant->setImageName($fileName);
            }

            $this->addFlash('success', 'Inscription réussie !');

            $entityManager->persist($participant);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $participant,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
