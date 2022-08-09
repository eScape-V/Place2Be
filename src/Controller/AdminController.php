<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Entity\Ville;
use App\Form\CampusType;
use App\Form\ParticipantType;
use App\Form\VilleType;
use App\Repository\CampusRepository;
use App\Repository\FileUploader;
use App\Repository\ParticipantRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{

    /**
     * @Route("/listeVilles", name="listeVilles")
     */
    public function listeVilles(VilleRepository $villeRepository): Response
    {
        $listeVilles = $villeRepository->findAllByAsc();

        return $this->render('admin/listeVilles.html.twig', [
            "listeVilles" => $listeVilles
        ]);
    }

    /**
     * @Route("/creerVille", name="creerVille")
     */
    public function creerVille(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Ville();
        $villeForm = $this->createForm(VilleType::class, $ville);

        if (!$this->getUser()) {
            $this->addFlash('error', 'Veuillez vous connecter pour ajouter une ville');
            return $this->redirectToRoute('app_login');
        }

        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'Ville ajoutée avec succès !');

            return $this->redirectToRoute('admin_listeVilles');

        }
        return $this->render('admin/creerVille.html.twig', [
            'villeForm' => $villeForm->createView()
        ]);
    }

    /**
     * @Route("/modifierVille/{id}", name="modifierVille")
     */
    public function modifierVille(EntityManagerInterface $entityManager, Ville $ville, Request $request): Response
    {
        $villeForm = $this->createForm(VilleType::class, $ville);
        $villeForm->handleRequest($request);

        if ($villeForm->isSubmitted() && $villeForm->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            $this->addFlash('success', 'Ville modifiée avec succès !');
            return $this->redirectToRoute('admin_listeVilles', ['id' => $ville->getId()]);
        }

        return $this->render('admin/modifierVille.html.twig', [
            'villeForm' => $villeForm->createView()
        ]);
    }

    /**
     * @Route("/supprimerVille/{id}", name="supprimerVille")
     */

    public function supprimerVille(Ville $ville, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($ville);
        $entityManager->flush();

        $this->addFlash('success', 'Ville supprimée avec succès !');

        return $this->redirectToRoute('main_home');
    }


    /**
     * @Route("/listeCampus", name="listeCampus")
     */
    public function listeCampus(CampusRepository $campusRepository): Response
    {
        $listeCampus = $campusRepository->findAllByAsc();

        return $this->render('admin/listeCampus.html.twig', [
            "listeCampus" => $listeCampus
        ]);
    }

    /**
     * @Route("/creerCampus", name="creerCampus")
     */
    public function creerCampus(Request $request, EntityManagerInterface $entityManager): Response
    {
        $campus = new Campus();
        $campusForm = $this->createForm(CampusType::class, $campus);

        if (!$this->getUser()) {
            $this->addFlash('error', 'Veuillez vous connecter pour créer un campus');
            return $this->redirectToRoute('app_login');
        }

        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();

            $this->addFlash('success', 'Campus crée avec succès !');

            return $this->redirectToRoute('admin_listeCampus');

        }
        return $this->render('admin/creerCampus.html.twig', [
            'campusForm' => $campusForm->createView()
        ]);
    }

    /**
     * @Route("/modifierCampus/{id}", name="modifierCampus")
     */
    public function modifierCampus(EntityManagerInterface $entityManager, Campus $campus, Request $request): Response
    {
        $campusForm = $this->createForm(CampusType::class, $campus);
        $campusForm->handleRequest($request);

        if ($campusForm->isSubmitted() && $campusForm->isValid()) {
            $entityManager->persist($campus);
            $entityManager->flush();

            $this->addFlash('success', 'Campus modifié avec succès !');
            return $this->redirectToRoute('admin_listeCampus', ['id' => $campus->getId()]);
        }

        return $this->render('admin/modifierCampus.html.twig', [
            'campusForm' => $campusForm->createView()
        ]);
    }

    /**
     * @Route("/supprimerCampus/{id}", name="supprimerCampus")
     */

    public function supprimerCampus(Campus $campus, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($campus);
        $entityManager->flush();

        $this->addFlash('success', 'Campus supprimé avec succès !');

        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/listeUtilisateurs", name="listeUtilisateurs")
     */
    public function listeUtilisateurs(ParticipantRepository $repo)
    {

        $utilisateurs = $repo->findAllByAsc();

        return $this->render('admin/listeUtilisateurs.html.twig', [
            "utilisateurs" => $utilisateurs
        ]);
    }

    /**
     * @Route("/creerUtilisateur", name="creerUtilisateur")
     */
    public function creerUtilisateur(Request $request,
                                     EntityManagerInterface $entityManager,
                                     UserPasswordHasherInterface $userPasswordHasher,
                                    FileUploader $fileUploader
                                    ): Response
    {
        $participant = new Participant();
        $participantForm = $this->createForm(ParticipantType::class, $participant);

        $participant->setAdministrateur(false);
        $participant->setActif(true);
        $participant->setRoles(["ROLE_USER"]);

        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted() && $participantForm->isValid()) {
            // encode the plain password
            $participant->setPassword(
                $userPasswordHasher->hashPassword(
                    $participant,
                    $participantForm->get('plainPassword')->getData()
                )
            );
            //Upload de l'image
            $file = $participantForm->get('imageFile')->getData();
            if ($file) {
                $fileName = $fileUploader->upload($file);
                $participant->setImageName($fileName);
            }
            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur crée avec succès !');


            return $this->redirectToRoute('admin_listeUtilisateurs');

            }
        return $this->render('admin/creerUtilisateurs.html.twig', [
            'participantForm' => $participantForm->createView()
        ]);
    }

    /**
     * @Route("/modifierUtilisateur/{id}", name="modifierUtilisateur")
     */
    public function modifierUtilisateur(EntityManagerInterface $entityManager, Participant $participant, Request $request): Response
    {
        $participantForm = $this->createForm(ParticipantType::class, $participant);
        $participantForm->handleRequest($request);

        if ($participantForm->isSubmitted() && $participantForm->isValid()) {
            $entityManager->persist($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès !');
            return $this->redirectToRoute('admin_listeUtilisateurs', ['id' => $participant->getId()]);
        }

        return $this->render('admin/modifierUtilisateurs.html.twig', [
            'participantForm' => $participantForm->createView()
        ]);
    }

    /**
     * @Route("/supprimerUtilisateur/{id}", name="supprimerUtilisateur")
     */

    public function supprimerUtilisateur(Participant $participant, EntityManagerInterface $entityManager)
    {
        if($participant->getOrganisateur() === true) {
            $this->addFlash('error', 'L\'utilisateur est l\'organisateur d\'une sortie !');
        } else {
            $entityManager->remove($participant);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur supprimé avec succès !');
        }

        return $this->redirectToRoute('admin_listeUtilisateurs');
    }

    /**
     * @Route("/desactiverUtilisateur/{id}", name="desactiverUtilisateur")
     */

    public function desactiverUtilisateur(Participant $participant, EntityManagerInterface $entityManager)
    {
        $participant->setActif(false);
        $entityManager->persist($participant);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur désactivé avec succès !');

        return $this->redirectToRoute('admin_listeUtilisateurs');
    }

    /**
     * @Route("/activerUtilisateur/{id}", name="activerUtilisateur")
     */

    public function activerUtilisateur(Participant $participant, EntityManagerInterface $entityManager)
    {
        $participant->setActif(true);
        $entityManager->persist($participant);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur activé avec succès !');

        return $this->redirectToRoute('admin_listeUtilisateurs');
    }
}
