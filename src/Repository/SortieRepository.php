<?php

namespace App\Repository;

use App\Entity\Sortie;

use App\Model\Recherche;
use DateTime;
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
     *
     * @param Recherche $recherche
     * @return array
     */

    public function rechercherSortie(Recherche $recherche): array
    {
        $queryBuilder = $this->createQueryBuilder('r')
            ->join('r.etat', 'e')
            ->leftJoin('r.participants', 'p');

        //si la case "Sorties passées" est cochée on affiche QUE les sorties passées sinon on n'affiche pas les sorties passées
        if (!$recherche->getPassees()) {
            $queryBuilder->andWhere('e.libelle != :etat2')
                ->setParameter('etat2', 'Passée');
        } else {
            $queryBuilder->andWhere('e.libelle = :etat3')
                ->setParameter('etat3', 'Passée');
        }

        //Les Roles User ne peuvent afficher des sortes passées depuis 1 mois max
        if ($recherche->getUser()->getRoles() == ["ROLE_USER"]) {

            $queryBuilder
                ->andWhere('r.dateHeureDebut > :date')
                ->setParameter('date', (new DateTime())->modify('-1 month'));

            $queryBuilder
                ->join("r.organisateur", "u")
                ->andWhere(" (r.organisateur = :organisateur) OR (e.libelle != :etat) ")
                ->setParameter("organisateur", $recherche->getUser()->getId())
                ->setParameter('etat', 'Créée');
        }

        //date Max de la sorties
        if ($recherche->getDateMax()) {
            $queryBuilder->andWhere('r.dateHeureDebut <= :dateMax');
            $queryBuilder->setParameter('dateMax', $recherche->getDateMax());
        }
        //date min de la sortie
        if ($recherche->getDateMin()) {
            $queryBuilder->andWhere('r.dateHeureDebut >= :dateMin');
            $queryBuilder->setParameter('dateMin', $recherche->getDateMin());
        }
        //rechercher si le nom de la sortie contient
        if ($recherche->getNom()) {
            $queryBuilder->andWhere('r.nom LIKE :nom');
            $queryBuilder->setParameter('nom', '%' . $recherche->getNom() . '%');
        }
        //n'afficher que les sorties dont le USER est organisateur.
        if ($recherche->getOrganisateur()) {
            $queryBuilder->andWhere('r.organisateur = :organisateur');
            $queryBuilder->setParameter('organisateur', $recherche->getUser()->getId());
        }
        //n'afficher que les sorite liées au site
        if ($recherche->getSite()) {
            $queryBuilder->Join('r.site', 's');
            $queryBuilder->andWhere('s.nom = :site');
            $queryBuilder->setParameter('site', $recherche->getSite()->getNom());
        }
        //si un toto clique sur inscrit et pas inscrit , dans le doute on affiche tout.
        if ($recherche->getInscrit() && $recherche->getPasInscrit()) {
            //sinon n'afficher que les sorties ou le USER est inscrit
        } else if ($recherche->getInscrit()) {
            $queryBuilder
                ->andWhere(' :participant MEMBER OF r.participants')
                ->setParameter('participant', $recherche->getUser()->getId());
            //sinon n'afficher que les sorties ou le USER n'est pas inscrit
        } else if ($recherche->getPasInscrit()) {
            $queryBuilder->andWhere(':participant  NOT MEMBER OF r.participants  ');
            $queryBuilder->setParameter('participant', $recherche->getUser()->getId());
        }


            // select pour récupérer les résultats
        $queryBuilder->addOrderBy('r.dateHeureDebut', 'ASC');
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
