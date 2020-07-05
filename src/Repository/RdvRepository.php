<?php

namespace App\Repository;

use App\Data\FiltreEntretienData;
use App\Data\FiltreRdv;
use App\Entity\Clinique;
use App\Entity\Entretien;
use App\Entity\Proprietaire;
use App\Entity\Rdv;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Security;

/**
 * @method Rdv|null find($id, $lockMode = null, $lockVersion = null)
 * @method Rdv|null findOneBy(array $criteria, array $orderBy = null)
 * @method Rdv[]    findAll()
 * @method Rdv[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RdvRepository extends ServiceEntityRepository
{

    private $security;
    private $container;

    public function __construct(ManagerRegistry $registry, Security $security, ContainerInterface $container)
    {
        parent::__construct($registry, Rdv::class);
        $this->security = $security;
        $this->container = $container;
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


    public function getByFilter(FiltreRdv $filtre)
    {

        $rdvsQuery = $this->createQueryBuilder('r')
                          ->join('r.veterinaire', 'v')
                          ->orderBy('v.nom', 'ASC')
                          ->addOrderBy('v.prenom', 'ASC')
        ;

        if ($this->security->isGranted($this->container->getParameter('ROLE_VETERINAIRE'))) {
            $rdvsQuery->where('r.veterinaire = :veterinaire')
                      ->setParameter('veterinaire', $this->security->getUser())
            ;
        }

        if ($this->security->isGranted($this->container->getParameter('ROLE_CLINIQUE'))) {
            $rdvsQuery->where('r.clinique = :clinique')
                      ->setParameter('clinique', $this->security->getUser()
                                                                ->getClinique())
            ;
        }


        $rdvsQuery = $this->filtre($rdvsQuery, $filtre);


        return $rdvsQuery->getQuery()
                         ->getResult()
            ;

    }


    private function filtre(QueryBuilder $query, FiltreRdv $filtre)
    {
        if (!empty($filtre->veterinaires)) {
            $query->andWhere('r.veterinaire in (:veterinaires)')
                  ->setParameter('veterinaires', $filtre->veterinaires)
            ;
        }


        return $query;
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
