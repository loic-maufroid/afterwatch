<?php

namespace App\Repository;

use App\Entity\Realisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Realisateur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Realisateur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Realisateur[]    findAll()
 * @method Realisateur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RealisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Realisateur::class);
    }

    // /**
    //  * @return Realisateur[] Returns an array of Realisateur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Realisateur
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
