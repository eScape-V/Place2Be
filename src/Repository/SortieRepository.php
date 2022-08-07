<?php

namespace App\Repository;

use App\Data\SearchData;
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
    public function findSearch(SearchData $searchData): array
    {

        //On récupère une requête avec la totalité
        $query = $this
            ->createQueryBuilder('s')
            ->select('c','s')
            ->join('s.campus', 'c')
        ;

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



        return $query->getQuery()->getResult();


//        Option qui marche que pour recherche si campus

//        $qb = $this->createQueryBuilder('p')
//
////            ->andWhere('p.campus = :campus')
////            ->setParameter('campus', $searchData->campus)
////            ->orderBy('p.campus', 'ASC');
//
//
//        $query = $qb->getQuery();
//
//        return $query->execute();

    }

//    public function findByCampus(int $id)
//    {
//        $queryBuilder = $this->createQueryBuilder('s');
//        $queryBuilder->andWhere('s.campus = '.$id);
//        $queryBuilder->addOrderBy('s.nom','DESC');
//        $query = $queryBuilder->getQuery(); $query->setMaxResults(50);
//        $results = $query->getResult();
//
//        return $results;
//
//    }

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

}
