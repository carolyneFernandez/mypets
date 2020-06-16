<?php

namespace App\Repository;

use App\Entity\VaccinType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VaccinType|null find($id, $lockMode = null, $lockVersion = null)
 * @method VaccinType|null findOneBy(array $criteria, array $orderBy = null)
 * @method VaccinType[]    findAll()
 * @method VaccinType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VaccinTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VaccinType::class);
    }

    // /**
    //  * @return VaccinType[] Returns an array of VaccinType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?VaccinType
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
