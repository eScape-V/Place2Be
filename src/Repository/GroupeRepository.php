<?php

namespace App\Repository;

use App\Entity\Groupe;
use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Groupe>
 *
 * @method Groupe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Groupe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Groupe[]    findAll()
 * @method Groupe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GroupeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Groupe::class);
    }

    public function add(Groupe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Groupe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function findAllGroupUser(Participant $user){
        $query = $this
            ->createQueryBuilder('groupe')
            ->select('c', 'groupe')
            ->join('groupe.createur', 'c')
            ->orderBy('groupe.nom', 'ASC');
        if ($user->getGroupe()){
            $query = $query
                ->andWhere('groupe IN (:campus)')
                ->setParameter('campus', $user->getCreateurGroupe());
        }
        return $query->getQuery()->getResult();
    }

//    /**
//     * @return Groupe[] Returns an array of Groupe objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Groupe
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function affichageGroupe(){
        $entityManager = $this->getEntityManager();
        $dql = "SELECT participants.*
        FROM App\Entity\Groupe g
        INNER JOIN participants
        ON participants_groupe.participants_id = participants.participants_id
        WHERE participants.groupe_id = ?";

        $query = $entityManager->createQuery($dql);

        return $query;
    }
}
