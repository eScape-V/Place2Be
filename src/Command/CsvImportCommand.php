<?php

namespace App\Command;

use App\Entity\Campus;
use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use League\Csv\Reader;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class CsvImportCommand extends Command
{
    private EntityManagerInterface $entityManager;

    private string $dataDirectory;

    private SymfonyStyle $io;

    private ParticipantRepository $participantRepository;

    public function __construct(EntityManagerInterface $entityManager, string $dataDirectory, ParticipantRepository $participantRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->dataDirectory = $dataDirectory;
        $this->participantRepository = $participantRepository;
    }

    protected static $defaultName = 'csv:import';

    protected function configure(): void
    {
        $this -> setDescription('Imports a mock CSV file');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->createUsers();

        return Command::SUCCESS;
    }

    private function getDataFromFile():array
    {
        $file = $this->dataDirectory.'mock_csv.csv';

        $fileExtension = pathinfo($file, PATHINFO_EXTENSION);

        $normalizers = [new ObjectNormalizer()];

        $encoders = [
            new CsvEncoder(),
            new XmlEncoder(),
            new YamlEncoder()
        ];

        $serializer = new Serializer($normalizers, $encoders);

        /** @var string $fileString */
        $fileString = file_get_contents($file);

        $data = $serializer->decode($fileString, $fileExtension);

        if(array_key_exists('results', $data)){
            return $data['results'];
        }
        return $data;

    }

    private function createUsers():void
    {
        $this->io->section('CREATION DES PARTICIPANTS A PARTIR DE FICHIER');

        $usersCreated = 0;

        foreach ($this->getDataFromFile() as $row) {
            if (array_key_exists('email', $row) && !empty($row['email'])){
                $user = $this->participantRepository->findOneBy([
                    'email' => $row['email']
                ]);

                if (!$user) {

                    //Je refais mon tableau des campus
                    $campusName = ['Paris-Sud', 'Marseille-Nord', 'Bordeaux III', 'Toulouse II', 'Lyon-Sud', 'Lille II', 'Strasbourg I', 'Bayonne'];
                    $campus = [];
                    for ($i = 0; $i < count($campusName); $i++)
                    {
                        $campus[$i] = new Campus();
                        $campus[$i]->setNom($campusName[$i]);
                    }

                    $user = new Participant();
                    $user
                        ->setCampus($campus [$row['campus_id']])
                        ->setEmail($row['email'])
                        ->setRoles(["ROLE_USER"])
                        ->setPassword($row['password'])
                        ->setNom($row['nom'])
                        ->setPrenom($row['prenom'])
                        ->setTelephone($row['telephone'])
                        ->setAdministrateur($row['administrateur'])
                        ->setActif($row['actif'])
                        ->setPseudo($row['pseudo']);

                    $this->entityManager->persist($user);

                    $usersCreated++;
                }
            }
        }

        $this->entityManager->flush();

        if ($usersCreated > 1){
            $string = "{$usersCreated} UTILISATEURS CREES EN BASE DE DONNEE";
        } elseif ($usersCreated === 1){
            $string = "1 UTILISATEUR A ETE CREE EN BASE DE DONNEE";
        } else {
            $string = "AUCUN UTILISATEUR N\'A ETE CREE EN BASE DE DONNEE";
        }
        $this->io->success($string);
    }
}