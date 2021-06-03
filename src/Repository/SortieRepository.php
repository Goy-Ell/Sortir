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
        $queryBuilder = $this->createQueryBuilder('r')
            ->join('r.etat', 'e')
            ->leftJoin('r.participants','p');



        if(!$recherche->getPassees()){
            $queryBuilder->andWhere('e.libelle != :etat2')
                ->setParameter('etat2','Passée');
        }

        if ( $recherche->getUser()->getRoles() == ["ROLE_USER"]) {

            $queryBuilder
                ->andWhere('r.dateHeureDebut > :date')
                ->setParameter('date', (new \DateTime())->modify('-1 month'));

            $queryBuilder
                ->join("r.organisateur", "u")
                ->andWhere(" (r.organisateur = :organisateur) OR (e.libelle != :etat) " )
                ->setParameter("organisateur", $recherche->getUser()->getId())
                ->setParameter('etat', 'Créée')
            ;
        }



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

        if($recherche->getSite()){
            $queryBuilder->Join('r.site', 's');
            $queryBuilder->andWhere('s.nom = :site');
            $queryBuilder->setParameter('site',$recherche->getSite()->getNom());
        }


        if($recherche->getInscrit()){
            $queryBuilder
                ->andWhere('p.id = :participant ')
                ->setParameter('participant',$recherche->getUser()->getId());
        }

        if($recherche->getPasInscrit()){
            $queryBuilder->andWhere('p.id != :participant ');
            $queryBuilder->setParameter('participant',$recherche->getUser()->getId());
        }


        // select pour récupérer les résultats
        $queryBuilder->addOrderBy('r.dateHeureDebut','ASC');
//        $queryBuilder->select('r');
        $query = $queryBuilder->getQuery();
        $sorties = $query->getResult();
        // compte le nombre de sorties trouvées
        $nbSorties = count($sorties);


        return [
            'sorties' => $sorties,
            'nbSorties' => $nbSorties
        ];
    }
}
