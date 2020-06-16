<?php

namespace App\Repository;

use App\Entity\CliniqueHoraire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CliniqueHoraire|null find($id, $lockMode = null, $lockVersion = null)
 * @method CliniqueHoraire|null findOneBy(array $criteria, array $orderBy = null)
 * @method CliniqueHoraire[]    findAll()
 * @method CliniqueHoraire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CliniqueHoraireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CliniqueHoraire::class);
    }

    // /**
    //  * @return CliniqueHoraire[] Returns an array of CliniqueHoraire objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CliniqueHoraire
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
