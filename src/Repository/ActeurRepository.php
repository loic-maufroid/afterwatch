<?php

namespace App\Repository;

use App\Entity\Acteur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Acteur|null find($id, $lockMode = null, $lockVersion = null)
 * @method Acteur|null findOneBy(array $criteria, array $orderBy = null)
 * @method Acteur[]    findAll()
 * @method Acteur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActeurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Acteur::class);
    }

    // /**
    //  * @return Acteur[] Returns an array of Acteur objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Acteur
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
