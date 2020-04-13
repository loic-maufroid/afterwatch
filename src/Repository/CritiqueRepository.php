<?php

namespace App\Repository;

use App\Entity\Critique;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @method Critique|null find($id, $lockMode = null, $lockVersion = null)
 * @method Critique|null findOneBy(array $criteria, array $orderBy = null)
 * @method Critique[]    findAll()
 * @method Critique[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CritiqueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Critique::class);
    }


    // /**
    //  * @return Critique[] Returns an array of Critique objects
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
    public function findOneBySomeField($value): ?Critique
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findWellReviewedFilmIds($num){

        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT c.id_film_id as idFilm, AVG(c.note) as moyenne FROM critique c WHERE c.publication = 1 GROUP BY c.id_film_id ORDER BY moyenne DESC LIMIT :num';
        $result = $conn->prepare($sql);
        $result->bindValue("num",$num,\PDO::PARAM_INT);
        $result->execute();

        if ($result)
        return $result;
        else
        return null;

    }

      /**
     * @return: Critique[]
     */
    public function findCritiquePaginator($page){
        $queryBuilder = $this->createQueryBuilder('c')
        ->orderBy('c.id','DESC')
        ->setFirstResult(($page-1) * 10)
        ->setMaxResults(10);

    return new Paginator($queryBuilder->getQuery());
    }

    /**
     * @return: int
     */
    public function findCountSubmittedCritiques(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT COUNT(*) as nombre FROM critique c WHERE c.publication = 0';
        $result = $conn->query($sql);

        return $result->fetch();
    }
}
