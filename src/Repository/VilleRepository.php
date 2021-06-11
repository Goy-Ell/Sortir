<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }

    /**
     * Recherche une ville selon un champs de saisi par la requete AJAX
     * @param string $saisi
     * @return int|mixed|string
     */
    public function rechercheVilleParSaisi(string $saisi)
    {
        $queryBuilder = $this->createQueryBuilder('v');
        $queryBuilder->andWhere('v.nom LIKE :rechercheVille')
            ->setParameter('rechercheVille', '%'.$saisi.'%')
            ->orderBy('v.nom','ASC');

        $queryBuilder->setMaxResults(20);
        $result=$queryBuilder->getQuery()->getResult();
////        dump($result);
//        $queryBuilder = $this->createQueryBuilder('v');
//        $queryBuilder->andWhere('v.nom LIKE :rechercheVille1')->setParameter('rechercheVille1', '%'.$saisi.'%');
//        $queryBuilder->setMaxResults(10);
////        array_push($result , $queryBuilder->getQuery()->getResult());
//        $result2 = $queryBuilder->getQuery()->getResult();
////        dump($result);
//        foreach ($result2 as $ville){
//            $result[]=$ville;
//        }
        return $result;
    }

    /**
     * Recherche le cp selon une ville
     * utilisé dans la requete AJAX
     * @param string $ville
     * @return int|mixed|string
     */
    public  function rechercheVille(string $ville)
    {
        $queryBuilder = $this->createQueryBuilder('v');
        $queryBuilder->andWhere('v.id = :ville')->setParameter('ville', $ville);
        return $queryBuilder->getQuery()->getResult();
    }

    // /**
    //  * @return Ville[] Returns an array of Ville objects
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
    public function findOneBySomeField($value): ?Ville
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
