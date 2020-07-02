<?php

namespace App\Repository;

use App\Entity\Clinique;
use App\Entity\Proprietaire;
use App\Entity\Rdv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;

/**
 * @method Rdv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rdv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rdv[]    findAll()
 * @method Rdv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RdvRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Rdv::class);
    }

    /**
     * @param Clinique $clinique
     * @return Rdv[] Returns an array of Rdv objects
     */
    public function findByClinique(Clinique $clinique)
    {
        return $this->createQueryBuilder('r')
                    ->join('r.veterinaire', 'v')
                    ->andWhere('v.clinique = :clinique')
                    ->setParameter('clinique', $clinique)
                    ->andWhere('r.completed = true')
                    ->orderBy('r.date', 'desc')
                    ->getQuery()
                    ->getResult()
            ;
    }

    /**
     * @param Proprietaire $proprietaire
     * @return Rdv[] Returns an array of Rdv objects
     * @throws Exception
     */
    public function findAVenirByProprietaire(Proprietaire $proprietaire)
    {
        $now = new \DateTime();

        return $this->createQueryBuilder('r')
                    ->andWhere('r.proprietaire = :proprietaire')
                    ->setParameter('proprietaire', $proprietaire)
                    ->andWhere('r.completed = true')
                    ->orderBy('r.date', 'desc')
                    ->andWhere('r.date >= :now')
                    ->setParameter('now', $now->format('Y-m-d 00:00:00'))
                    ->getQuery()
                    ->getResult()
            ;
    }

    /*
    public function findOneBySomeField($value): ?Rdv
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
