<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }


    /**
     * @return Sortie[]
     */
    public function findSearch(SearchData $searchData, Participant $user): array
    {

//        //On récupère une requête avec la totalité
//        $query = $this
//            ->createQueryBuilder('s')
//            ->select('c', 's')
//            ->join('s.campus', 'c')
//            ->orderBy('s.dateHeureDebut', 'ASC');

        //On récupère une requête avec la totalité
        $query = $this
            ->createQueryBuilder('s')
            ->select('c', 's')
            ->join('s.campus', 'c')
            ->orderBy('s.dateHeureDebut', 'ASC');


        //Filtrer avec les campus
        if (!empty($searchData->campus)) {
            $query = $query
                ->andWhere('c.id IN (:campus)')
                ->setParameter('campus', $searchData->campus);
        }

        //Filtrer avec la barre de recherche
        if (!empty($searchData->q)) {
            $query = $query
                ->andWhere('s.nom LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }

        //Filtrer avec la date minimale
        if (!empty($searchData->dateMin)) {
            $query = $query
                ->andWhere('s.dateHeureDebut >= :dateMin')
                ->setParameter('dateMin', $searchData->dateMin);
        }

        //Filtrer avec la date maximale
        if (!empty($searchData->dateMax)) {
            $query = $query
                ->andWhere('s.dateHeureDebut <= :dateMax')
                ->setParameter('dateMax', $searchData->dateMax);
        }

        //Filtrer si l'utilisateur est organisateur
        if ($searchData->isOrganisateur == 1){
                $query = $query
                ->andWhere('s.organisateur = :organisateur')
                ->setParameter('organisateur', $user->getId());
        }

        //Filtrer si l'utilisateur est inscrit
        if ($searchData->isInscrit == 1){
            $query = $query
                ->andWhere('s IN (:inscrit)')
                ->setParameter('inscrit', $user->getSortie());
        }

        //Filtrer si l'utilisateur n'est pas inscrit
        if ($searchData->isNotInscrit == 1){
            $query = $query
                ->andWhere('s NOT IN (:inscrit)')
                ->setParameter('inscrit', $user->getSortie());
        }

        //Filtrer si les sorties sont passées (méthode 'datetime')
        if ($searchData->passees == 1){
            $query = $query
                ->andWhere('s.dateHeureDebut <= :dateNow')
                ->setParameter('dateNow', new \DateTime());
        }

        return $query->getQuery()->getResult();
    }

    public function add(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByCampusUser(Participant $user){
        $query = $this
            ->createQueryBuilder('s')
            ->select('c', 's')
            ->join('s.campus', 'c')
            ->orderBy('s.dateHeureDebut', 'ASC');
        if ($user->getSortie()){
            $query = $query
                ->andWhere('s IN (:campus)')
                ->setParameter('campus', $user->getCampus()->getSortie());
        }
        return $query->getQuery()->getResult();

    }

}
