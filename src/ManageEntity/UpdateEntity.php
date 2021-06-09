<?php


namespace App\ManageEntity;

//use App\Entity\Sortie;
use App\Repository\EtatRepository;
//use App\Repository\SortieRepository;
use App\Entity\Sortie;
use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;


class UpdateEntity
{
    private $entityManager;
    private $etatRepository;
//    private $sortieRepository;
    public function __construct(EntityManagerInterface $entityManager
        ,EtatRepository $etatRepository
//        ,SortieRepository $sortieRepository
    )
    {
        $this->entityManager= $entityManager;
        $this->etatRepository=$etatRepository;
//        $this->sortieRepository=$sortieRepository;
    }

    /**
     * @param $listeMaj
     * @param $etats
     * @throws Exception
     * @var $sortie Sortie
     */
    public function majEtat($listeMaj, $etats)
    {

        foreach ($listeMaj as $sortie) {

            if ($sortie->getEtat()->getLibelle() != 'Passée' &&
                $sortie->getEtat()->getLibelle() != 'Annulée' &&
                $sortie->getEtat()->getLibelle() != 'Cloturé' &&
                $sortie->getEtat()->getLibelle() != 'Activité en cours' &&
                ((count($sortie->getParticipants()) >= $sortie->getNbInscriptionMax()) || $sortie->getDateLimiteInscription() < new DateTime())) {
                    $sortie->setEtat($etats[2]);
                    $this->entityManager->persist($sortie);
                dump('bou1');
            }

            if ($sortie->getEtat()->getLibelle() != 'Passée' &&
                $sortie->getEtat()->getLibelle() != 'Annulée' &&
                $sortie->getEtat()->getLibelle() != 'Cloturée' &&
                $sortie->getDateHeureDebut() < (new DateTime()) &&
                $sortie->getDateHeureDebut()->add(new DateInterval('PT' . $sortie->getDuree() . 'M')) > new DateTime()) {
                    $sortie->setEtat($etats[3]);
                    $this->entityManager->persist($sortie);
                dump('bou2');
            }

            if ($sortie->getEtat()->getLibelle() != 'Passée' &&
                $sortie->getEtat()->getLibelle() != 'Annulée' &&
                $sortie->getDateHeureDebut()->add(new DateInterval('PT' . $sortie->getDuree() . 'M')) < new DateTime()            ) {
                    $sortie->setEtat($etats[4]);
                    $this->entityManager->persist($sortie);
                dump('bou3');
            }

            if ($sortie->getEtat()->getLibelle() == 'Cloturée' &&
                (new DateTime()) < $sortie->getDateLimiteInscription() &&
                (count($sortie->getParticipants()) < $sortie->getNbInscriptionMax())) {
                $sortie->setEtat($etats[1]);
                $this->entityManager->persist($sortie);
                dump('bou4');
            }

        }
        $this->entityManager->flush();
    }





    public function supprimerSortie($sortie){

    }
    public function supprimerUser($user){

    }
    public function annulerSortie($sortie){
        $etats = $this->etatRepository->findAll();

        if ($sortie->getEtat() != 'Passée' &&
            $sortie->getEtat() != 'Annulée' &&
            $sortie->getEtat() != 'Cloturé' &&
            $sortie->getEtat() != 'Activité en cours'){

            $sortie->setEtat($etats[5]);
            $this->entityManager->persist($sortie);
        }
    }
    public function validerSortie($sortie){

        $etats = $this->etatRepository->findAll();

        if ($sortie->getEtat() != 'Créée') {

            $sortie->setEtat($etats[1]);
            $this->entityManager->persist($sortie);
        }
    }

}