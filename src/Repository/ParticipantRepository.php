<?php

namespace App\Repository;

use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Participant[] Returns an array of Participant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Participant
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function rechercherSortie(\App\Entity\Recherche $recherche)
    {
        $queryBuilder=$this->createQueryBuilder('r');
        $queryBuilder->addOrderBy('h.dateHeureDebut');

        if($recherche->getDateMax()){
            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->get)
        }
        if($recherche->getDateMin()){
            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->get)
        }
        if($recherche->getInscrit()){
            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->get)
        }
        if($recherche->getNom()){
            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->get)
        }
        if($recherche->getOrganisateur()){
            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->get)
        }
        if($recherche->getPasInscrit()){
            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->get)
        }
        if($recherche->getPassees()){
            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->get)
        }
        if($recherche->getSite()){
            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->get)
        }



    }*/
}
