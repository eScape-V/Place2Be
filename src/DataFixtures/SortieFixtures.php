<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Repository\FileUploader;
use Doctrine\Bundle\FixturesBundle\Fixture;

use Doctrine\Persistence\ObjectManager;

use Faker;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\ByteString;
use Symfony\Component\String\UnicodeString;


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

        // Création de 4 états
        $libelles = ['Créée', 'Ouverte', 'Clôturée', 'En cours', 'Terminée', 'Annulée'];

        $etat = [];

        for ($i = 0; $i < count($libelles); $i++)
        {
            $etat[$i] = new Etat();
            $etat[$i]->setLibelle($libelles[$i]);
            $manager->persist($etat[$i]);
        }

        // Création de 10 campus dont le premier est par défaut
        $campus = [];
        $campus[0] = new Campus();
        $campus[0]->setNom('Non attribué');

        for ($i = 1; $i < 5; $i++)
        {
            $campus[$i] = new Campus();
            $campus[$i]->setNom($faker->company);
            $manager->persist($campus[$i]);
        }

        // Création de 50 participants
        $participant = [];

        for ($i = 0; $i < 50; $i++)
        {
            $participant[$i] = new Participant();
            $participant[$i]->setPrenom($faker->firstName);
            $participant[$i]->setNom($faker->lastName);
            $participant[$i]->setTelephone("06".rand(00000000, 99999999));
            $participant[$i]->setEmail($faker->email);
            $participant[$i]->setPassword($faker->password);
            $participant[$i]->setActif(true);
            $participant[$i]->setPseudo($faker->userName);
            $participant[$i]->setCampus($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $participant[$i]->setRoles(["ROLE_USER"]);
//
//            $finder = new Finder();
//            $finder->files()->in(__DIR__.'/images/');
////            $filenames = iterator_to_array($finder);
//            $participant[$i]->setImageFile($i.".jpg");
////
////            dd($filenames);
////
///
////
//////            dd($fileNameWithExtension);
//            $file = new File();
//////
//
//            $participant[$i]->setImageFile($i.".jpg");
//            dd($participant[$i]);
////            $finder->in(__DIR__.'\images/'.$i.'.jpg');
////            if ($finder->hasResults()) {
////                // ...
////            }
////
//            foreach ($finder as $file) {
//                $absoluteFilePath = $file->getRealPath();
//                $fileNameWithExtension = $file->getRelativePathname();
//                $participant[$i]->setImageName($absoluteFilePath);
//                // ...
//            }

//            $participant[$i]->setImageName($absoluteFilePath);



//            $file = new File(__DIR__.'/images/'.$i.'.jpg');
////            if($file) {
////                $fileName = $fileUploader->upload($file);
//                $participant->setImageName($file);
//            }
//
//            $randomImage = $this->faker->randomElement(self::$articleImages);
//            $imageFilename = $this->uploaderHelper
//                ->uploadArticleImage(new File(__DIR__.'/images/'.$randomImage));
//            $participant[$i]->setImageName($imageFilename)
//            ;
////            $fileName = new File(__DIR__.'/images/'.$i.'.jpg');
////            $participant[$i]->setImageFile($fileName);


//            $fileUploader= null;
//            $fileName = $fileUploader->upload(__DIR__.'/images/'.$i.'.jpg');
//            $participant[$i]->setImageName($fileName);


//            $file = $file->get('/images/profil_img/'.$i.'.jpg')->getData();
//            if($file) {
//                $fileName = $fileUploader->upload($file);
//                $participant->setImageName($fileName);
//            }
////
//            $participant[$i]->setImageFile((new File('/images/profil_img/'.$i.'.jpg')));

            $manager->persist($participant[$i]);
        }

        // Création de 30 sorties
        $sortie = [];

        for ($i = 0; $i < 30; $i++) {
            $sortie[$i] = new Sortie();
            $sortie[$i]->setNom($faker->sentence($nbWords = 4, $variableNbWords = true));
            $sortie[$i]->setdateHeureDebut($faker->dateTimeInInterval($startDate = '+ 10 days', $interval = '+20 day', $timezone = null));
            $sortie[$i]->setDuree($faker->numberBetween($min = 1, $max = 8));
            $sortie[$i]->setDateLimiteInscription($faker->dateTimeInInterval($startDate = 'now', $interval = '+10 day', $timezone = null));
            $sortie[$i]->setNbInscriptionsMax($faker->numberBetween($min = 5, $max = 20));
            $sortie[$i]->setInfosSortie($faker->sentence);

            $sortie[$i]->setEtat($etat[0]);
            $sortie[$i]->setLieu($lieu[$faker->numberBetween($min = 0, $max = count($lieu) - 1)]);
            $sortie[$i]->setCampus($campus[$faker->numberBetween($min = 0, $max = count($campus) - 1)]);
            $sortie[$i]->setOrganisateur($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            for ($j = 0; $j < $faker->numberBetween($min = 0, $max = $sortie[$i]->getNbInscriptionsMax()); $j++) {
                $sortie[$i]->addParticipant($participant[$faker->numberBetween($min = 0, $max = count($participant) - 1)]);
            }
            $manager->persist($sortie[$i]);
        }
//        for($i = 1; $i <=10; $i++) {
//            $sortie = new Sortie();
//            $sortie -> setNom("Nom de l'article n° $i")
//                    -> setDateHeureDebut(new \DateTime())
//                    -> setDuree(100)
//                    -> setDateLimiteInscription(new \DateTime())
//                    -> setNbInscriptionsMax(10)
//                    -> setEtat()
//                    -> setOrganisateur("Albert")
//                    -> setInfosSortie("Infos sorties")
//                    -> setCampus("Rennes")
//                    -> setLieu("Bordeaux");
//        }
        // $product = new Product();
        $manager->flush();
    }

}
