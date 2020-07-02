<?php

namespace App\Repository;

use App\Entity\VeterinaireHoraire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method VeterinaireHoraire|null find($id, $lockMode = null, $lockVersion = null)
 * @method VeterinaireHoraire|null findOneBy(array $criteria, array $orderBy = null)
 * @method VeterinaireHoraire[]    findAll()
 * @method VeterinaireHoraire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VeterinaireHoraireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VeterinaireHoraire::class);
    }

    // /**
    //  * @return VeterinaireHoraire[] Returns an array of VeterinaireHoraire objects
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
    public function findOneBySomeField($value): ?VeterinaireHoraire
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
