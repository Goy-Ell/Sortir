<?php

namespace App\Repository;

use App\Entity\Sortie;
use App\Entity\Site;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    /**
     * @var User $user
     */

    public function rechercherSortie(\App\Model\Recherche $recherche): array
    {
        $queryBuilder=$this->createQueryBuilder('r');




        if($recherche->getDateMax()){
            $queryBuilder->andWhere('r.dateHeureDebut <= :dateMax');
            $queryBuilder->setParameter('dateMax',$recherche->getDateMax());
        }
        if($recherche->getDateMin()){
            $queryBuilder->andWhere('r.dateHeureDebut >= :dateMin');
            $queryBuilder->setParameter('dateMin',$recherche->getDateMin());
        }


        if($recherche->getNom()){
            $queryBuilder->andWhere('r.nom LIKE :nom');
            $queryBuilder->setParameter('nom', '%'.$recherche->getNom().'%' );
        }

        if($recherche->getOrganisateur()){
            $queryBuilder->andWhere('r.organisateur = :organisateur');
            $queryBuilder->setParameter('organisateur',$recherche->getUser()->getId());
        }


        if($recherche->getPassees()){
            $dateNow=New \DateTime();
//            $queryBuilder->andWhere('h.HeureDebut < :dateNow');
            $queryBuilder->andWhere('r.etat = :etat');
            $queryBuilder->andWhere('r.dateHeureDebut > :dateNow1m');
//            $queryBuilder->setParameter('dateNow',$dateNow);
            $queryBuilder->setParameter('etat','Passée');
            $queryBuilder->setParameter('dateNow1m',$dateNow->modify('-1 month'));
        }
        if($recherche->getSite()){
//        dd($recherche->getSite()->getNom());
            $queryBuilder->Join('r.site', 's')
                ->addSelect('s');
            $queryBuilder->andWhere('s.nom = :site');
            $queryBuilder->setParameter('site',$recherche->getSite()->getNom());
        }

        //TODO
        if($recherche->getInscrit()){

            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->getInscrit());
        }
//TODO
        if($recherche->getPasInscrit()){
            $queryBuilder->andWhere('');
            $queryBuilder->setParameter('',$recherche->getPasInscrit());
        }

        //permet de récupérer le nombre de résultat
        $queryBuilder->select('COUNT(r)');
        $countQuery = $queryBuilder->getQuery();
        $nbSorties = $countQuery->getSingleScalarResult();

        //doit refaire le select pour bien récupérer les résultats
        $queryBuilder->addOrderBy('r.dateHeureDebut','ASC');
        $queryBuilder->select('r');
        $query = $queryBuilder->getQuery();



//        $query->setMaxResults(20);
//        $query->setFirstResult($offset);


        return [
            'sorties' => $query->getResult(),
            'nbSorties' => $nbSorties
        ];


    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

}
