<?php

namespace App\Repository;

use App\Entity\Film;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Film|null find($id, $lockMode = null, $lockVersion = null)
 * @method Film|null findOneBy(array $criteria, array $orderBy = null)
 * @method Film[]    findAll()
 * @method Film[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FilmRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Film::class);
    }

    // /**
    //  * @return Film[] Returns an array of Film objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Film
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Film[]
     */
    public function findByTitle($title){

        $result = $this->getEntityManager()
        ->createQuery('SELECT f FROM App\Entity\Film f WHERE f.titre LIKE :title')
        ->setParameter('title','%'.$title.'%')
        ->getResult();

        return $result;

    }

    /**
     * @return int[]
     */
    public function findIdsByDirector($director){
        
        $result = $this->getEntityManager()
        ->createQuery("SELECT r.id FROM App\Entity\Realisateur r WHERE CONCAT(r.prenom,' ',r.nom) LIKE :director")
        ->setParameter('director','%'.$director.'%')
        ->getResult();

        return $result;        
    }

    /**
     * @return int[]
     */
    public function findIdsByActor($actor){
        $result = $this->getEntityManager()
        ->createQuery("SELECT a.id FROM App\Entity\Acteur a WHERE CONCAT(a.prenom,' ',a.nom) LIKE :actor")
        ->setParameter('actor','%'.$actor.'%')
        ->getResult();

        return $result;     
    }

     /**
     * @return []
     */
    public function findSevenRandomIds(){
        $conn = $this->getEntityManager()->getConnection();

        $sql = 'SELECT f.id FROM film f ORDER BY RAND() LIMIT 7';
        $result = $conn->query($sql);

        return $result->fetchAll();
    }

    /**
     * @return: Film[]
     */
    public function findSevenRandomReleasedFilms(){
        $ids = $this->findSevenRandomIds();
        $tabid = array("id1" => $ids[0]["id"],"id2" => $ids[1]["id"],"id3" => $ids[2]["id"],"id4" => $ids[3]["id"],"id5" => $ids[4]["id"],"id6" => $ids[5]["id"],"id7" => $ids[6]["id"]);

        $query = $this->getEntityManager()->createQuery(
            'SELECT f FROM App\Entity\Film f WHERE f.id IN(:id1,:id2,:id3,:id4,:id5,:id6,:id7)'
        )
        ->setParameters($tabid);

        return $query->getResult();
    }

    //Requete personnalisé pour réduire le nombre de requete du site
    public function findAllFilms()
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->leftJoin('f.acteur_joue', 'a')
            ->leftJoin('f.genre_film', 'g')
            ->leftJoin('f.realise', 'r')
            ->leftJoin('f.scenario', 's')
            ->addSelect('a', 'g', 'r', 's')
            ->getQuery();

        return $queryBuilder->getResult();
    }

}
