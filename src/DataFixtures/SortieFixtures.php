<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\Persistence\ObjectManager;

use Faker;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\ByteString;
use Symfony\Component\String\UnicodeString;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class SortieFixtures extends Fixture
{
    private static $articleImages = [
        '0.jpg',
        '1.jpg',
        '2.jpg',
        '3.jpg',
        '4.jpg',
        '5.jpg',
        '6.jpg',
        '7.jpg',
        '8.jpg',
        '9.jpg',
        '10.jpg',
        '11.jpg',
        '12.jpg',
        '13.jpg',
        '14.jpg',
        '15.jpg',
        '16.jpg',
        '17.jpg',
        '18.jpg',
        '19.jpg',
        '20.jpg',
        '21.jpg',
        '22.jpg',
        '23.jpg',
        '24.jpg',
        '25.jpg',
        '26.jpg',
        '27.jpg',
        '28.jpg',
        '29.jpg',
        '30.jpg',
        '31.jpg',
        '32.jpg',
        '33.jpg',
        '34.jpg',
        '35.jpg',
        '36.jpg',
        '37.jpg',
        '38.jpg',
        '39.jpg',
        '40.jpg',
        '41.jpg',
        '42.jpg',
        '43.jpg',
        '44.jpg',
        '45.jpg',
        '46.jpg',
        '47.jpg',
        '48.jpg',
        '49.jpg',
        '50.jpg',
    ];

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {

        $faker = Faker\Factory::create('fr_FR');

        // Création de 20 villes
        $ville = [];

        for ($i = 0; $i < 20; $i++)
        {
            $ville[$i] = new Ville();
            $ville[$i]->setNom($faker->city());
            $ville[$i]->setCodePostal("XXXXX");
            $manager->persist($ville[$i]);
        }

        // Création de 30 lieux
        $lieu = [];

        for ($i = 0; $i < 30; $i++)
        {
            $lieu[$i] = new Lieu();
            $lieu[$i]->setNom($faker->city);
            $lieu[$i]->setRue($faker->streetName);
            $lieu[$i]->setLatitude($faker->latitude($min = -90, $max = 90));
            $lieu[$i]->setLongitude($faker->longitude($min = -180, $max = 180));
            $lieu[$i]->setVille($ville[$faker->numberBetween($min = 0, $max = count($ville) -1)]);
            $manager->persist($lieu[$i]);
        }

        // Création de 6 états
        $libelles = ['Créée', 'Ouverte', 'Clôturée', 'En cours', 'Terminée', 'Annulée'];

        $etat = [];

        for ($i = 0; $i < count($libelles); $i++)
        {
            $etat[$i] = new Etat();
            $etat[$i]->setLibelle($libelles[$i]);
            $manager->persist($etat[$i]);
        }

        // Création de 8 vrais campus existants
        $campusName = ['Paris-Sud', 'Marseille-Nord', 'Bordeaux III', 'Toulouse II', 'Lyon-Sud', 'Lille II', 'Strasbourg I', 'Bayonne'];
        $campus = [];
        for ($i = 0; $i < count($campusName); $i++)
        {
            $campus[$i] = new Campus();
            $campus[$i]->setNom($campusName[$i]);
            $manager->persist($campus[$i]);
        }

        // Création de 50 participants
        $participant = [];

        //Création d'un participant 'user'
        $participant[0] = new Participant();
        $participant[0]->setPrenom("user");
        $participant[0]->setNom("user");
        $participant[0]->setTelephone("06".rand(00000000, 99999999));
        $participant[0]->setEmail("user@user.com");
//        $participant[0]->setPassword(hashPassword("Azerty0!"));
        $participant[0]->setActif(true);
        $participant[0]->setPseudo("user");
        $participant[0]->setCampus($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
        $participant[0]->setRoles(["ROLE_USER"]);

        $sPlainPassword = "Azerty0!";
        $hash = $this->hasher->hashPassword($participant[0], $sPlainPassword);
        $participant[0]->setPassword($hash);

        $manager->persist($participant[0]);

        // Création d'un participant "admin"
        $participant[1] = new Participant();
        $participant[1]->setPrenom("admin");
        $participant[1]->setNom("admin");
        $participant[1]->setTelephone("06".rand(00000000, 99999999));
        $participant[1]->setEmail("admin@admin.com");
//        $participant[51]->setPassword("Azerty0!");
        $participant[1]->setActif(true);
        $participant[1]->setPseudo("admin");
        $participant[1]->setCampus($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
        $participant[1]->setRoles(["ROLE_ADMIN"]);

        $hash = $this->hasher->hashPassword($participant[1], $sPlainPassword);
        $participant[1]->setPassword($hash);

        $manager->persist($participant[1]);

        //Création des 48 autres participants fictifs

        for ($i = 2; $i < 50; $i++)
        {
            $participant[$i] = new Participant();
            $participant[$i]->setPrenom($faker->firstName);
            $participant[$i]->setNom($faker->lastName);
            $participant[$i]->setTelephone("06".rand(00000000, 99999999));
            $participant[$i]->setEmail($faker->email);
//            $participant[$i]->setPassword($faker->password);
            $participant[$i]->setActif(true);
            $participant[$i]->setPseudo($faker->userName);
            $participant[$i]->setCampus($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $participant[$i]->setAdministrateur(false);
            $participant[$i]->setImageName("$i.jpg");
            $participant[$i]->setRoles(["ROLE_USER"]);

            $sPlainPassword = "Azerty0!";
            $hash = $this->hasher->hashPassword($participant[$i], $sPlainPassword);
            $participant[$i]->setPassword($hash);

            $manager->persist($participant[$i]);
        }

        // Création de 30 sorties
        $sortie = [];

        for ($i = 0; $i < 30; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));

            //Création d'une variable date/heure de début entre -10 jours (from now) à +20 jours (from now)
            $dateHeureDebut = $faker->dateTimeInInterval($startDate = '-5 day', $interval = '+20 day', $timezone = null);
            $sortie[$i]->setdateHeureDebut($dateHeureDebut);

            $duree = $faker->numberBetween($min = 30, $max = 240);
            $sortie[$i]->setDuree($duree);

            //Création d'une variable date limite d'inscription aléatoire entre 0 et 10 jours avant début de la date/heure de début
            $dateLimiteInscription = $faker->dateTimeInInterval($startDate = $dateHeureDebut, $interval = '-2 day', $timezone = null);
            $sortie[$i]->setDateLimiteInscription($dateLimiteInscription);

            $sortie[$i]->setNbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            //Création de l'état en fonction de la date limite d'inscription, date/heure de début et date du jour
            $now = new \DateTime();
            $dateOuvertureInscription = $faker->dateTimeInInterval($startDate = $dateLimiteInscription, $interval = '-12 day', $timezone = null);

//            $dureeString = trim($duree).'i';
//            $dateHeureDebutString = trim($dateHeureDebut);
//
//            $dureeTime = new \DateInterval($dureeString);
////            dd($dureeTime); retourne les minutes
//
//            $period = new \DatePeriod($dateHeureDebut, $dureeTime, 1 );
////            dd($period); return ok toutes les infos
//
//            $dateFinDeSortie = new \DateTime($dateHeureDebutString.'+'.$dureeString);
////            dd($dateFinDeSortie);


            if ($dateHeureDebut <= $now){
                $sortie[$i]->setEtat($etat[4]);
            }elseif ($dateLimiteInscription <= $now){
                $sortie[$i]->setEtat($etat[2]);
            } elseif ($dateOuvertureInscription < $now){
                $sortie[$i]->setEtat($etat[1]);
            } else
                $sortie[$i]->setEtat($etat[0]);

            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setCampus($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);

            if ($sortie[$i]->getEtat() == $etat[0]){
                $sortie[$i]->addParticipant($sortie[$i]->getOrganisateur());
//                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            } else {
                for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getNbInscriptionsMax()); $j++) {
                    $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
                }
            }

            $manager->persist($sortie[$i]);
        }
        $manager->flush();
    }

}
