<?php

namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\AnnulationSortieType;
use App\Form\LieuType;
use App\Form\RechercheType;
use App\Form\SortieType;
use App\ManageEntity\UpdateEntity;
use App\Model\Recherche;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class SortieController extends AbstractController
{
    //Creer une sortie
    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(Request $request,
                            EntityManagerInterface $entityManager,
                            EtatRepository $etatRepository,
                            LieuRepository $lieuRepository
                            ): Response
    {
        $sortie = new Sortie();
        $sortie->setOrganisateur($this->getUser());
        $sortieEtat = $etatRepository->findOneBy(['libelle'=> 'Créée']);
        $sortie->setEtat($sortieEtat);
        $sortie->setSite($this->getUser()->getSite());

        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            //recupere le lieu en dehors du SortieType.php
            $idLieu = $_REQUEST['lieuxSelect'];
            $lieuChoisi = $lieuRepository->findOneBy(['id'=>$idLieu]);
            $sortie->setLieu($lieuChoisi);


            /**
             * Gestion du telechargement de la photo de sortie
             * @var  UploadedFile file
             */
            $file = $sortieForm->get('photoSortie')->getData();
            if ($file){
                $directory = $this->getParameter('upload_photo_sortie_dir');

                //rename files - parametre uniqid special ordi nico !!
                $newFileName = $sortie->getNom().'-'.uniqid('', true).'.'.$file->guessExtension();
                //save in to directory (+ modified service.yaml)
                $file->move($directory, $newFileName);
                //save name in BDD
                $sortie->setPhoto($newFileName);
            }

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie ajoutée ! ');

            return $this->redirectToRoute('sortie_recherche');
        }
        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

    //Publier une sortie
    /**
     * @Route("/sortie/publier{id}", name="sortie_publier")
     */
    public function publierSortie($id, SortieRepository $sortieRepository, UpdateEntity $updateEntity, EntityManagerInterface $entityManager){

        //On récupère le user
        $user = $this->getUser();

        //On recupere sortie repository
        $sorties = $sortieRepository->find($id);

        $updateEntity->validerSortie($sorties);

        $entityManager->persist($sorties);
        $entityManager->flush();

        $this->addFlash('success', 'Sortie publiée ! ');

        return $this->render('sortie/detail.html.twig', [
            'sorties' => $sorties
        ]);
    }

    //Annuler une sortie
    /**
     * @Route("/sortie/annuler{id}", name="sortie_annuler")
     */
    public function annulerSortie($id,
                                  Request $request,
                                  SortieRepository $sortieRepository,
                                  UpdateEntity $updateEntity,
                                  EntityManagerInterface $entityManager){

        //On récupère le user
        $user = $this->getUser();

        //On recupere sortie repository
        $sortie = $sortieRepository->find($id);

        $annulationSortieForm = $this->createForm(AnnulationSortieType::class, $sortie);
        $annulationSortieForm->handleRequest($request);

        if ($annulationSortieForm->isSubmitted() && $annulationSortieForm->isValid()) {


            $updateEntity->annulerSortie($sortie);

            $entityManager->persist($sortie);
            $entityManager->flush();

            $this->addFlash('success', 'Sortie annulée ! ');
            return $this->redirectToRoute('sortie_recherche');
        }
        return $this->render('sortie/delete.html.twig', [
            'sortie' => $sortie,
            'annulationSortieForm' => $annulationSortieForm->createView(),
        ]);
    }

//    //Créer un nouveau lieu pour une sortie
//    /**
//     * @Route("/lieu/create", name="lieu_create")
//     */
//
//    public function ajouterLieu(Request $request,
//                                EntityManagerInterface $entityManager):Response
//    {
//        $lieu = new Lieu();
//
//        $lieuForm = $this->createForm(LieuType::class, $lieu);
//
//        $lieuForm->handleRequest($request);
//
//        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
//
//            $entityManager->persist($lieu);
//            $entityManager->flush();
//
//            $this->addFlash('success', 'Lieu ajouté !');
//
//            return $this->redirectToRoute('sortie_create');
//
//        }
//
//        return $this->render('lieu/create.html.twig', [
//            'lieuForm' => $lieuForm->createView(),
//            'page'=> 1
//        ]);
//    }



    //Afficher les details d'une sortie
    /**
     * @Route("/sortie/detail/{id}", name="sortie_detail", requirements={"id"="\d+"})
     */

    public function detail($id, SortieRepository $sortieRepository): Response
    {

        $sorties = $sortieRepository->find($id);

        if(!$sorties){
            throw $this->createNotFoundException(("Cette sortie n'existe pas ! "));
        }

        return $this->render('sortie/detail.html.twig', [
            "sorties" => $sorties
        ]);
    }

    //Inscription à une sortie

    /**
     * @Route("/sortie/recherche/inscription{id}", name="sortie_inscription")
     * @param UserRepository $userRepository
     * @return Response
     */

    public function inscriptionSortie($id, UserRepository $userRepository,
                                      SortieRepository $sortieRepository,
                                      EntityManagerInterface $entityManager):Response
    {

        $sorties = $sortieRepository->find($id);
        //verification si la sortie est bien recuperer
        if (!$sorties){
            throw $this->createNotFoundException("Sortie non trouvée !!");
        }

        //On recupere le user
        $user = $this->getUser();

        if($sorties->getParticipants()->contains($user)){
            $this->addFlash('success', 'Vous êtes déjà inscrit à cette sortie ! ');
            return $this->redirectToRoute('sortie_recherche');
        }

        //Ajout du user en participant de la sortie
        $sorties->addParticipant($user);

        $entityManager->persist($sorties);
        $entityManager->flush();

        $this->addFlash('success', 'Felicitation, vous vous êtes bien inscrit ! ');


        return $this->render('sortie/detail.html.twig', [
            "sorties" => $sorties
        ]);
    }

    //Se desister d'une sortie
    /**
     * @Route("/sortie/recherche/desistement{id}", name="sortie_desistement")
     * @param UserRepository $userRepository
     * @return Response
     */

    public function desistementSortie($id, UserRepository $userRepository,
                                      SortieRepository $sortieRepository,
                                      EntityManagerInterface $entityManager):Response
    {

        $sorties = $sortieRepository->find($id);
        //verification si la sortie est bien recuperer
        if (!$sorties){
            throw $this->createNotFoundException("Sortie non trouvée !!");
        }

        //On recupere le user
        $user = $this->getUser();

        if(!$sorties->getParticipants()->contains($user)){
            $this->addFlash('success', 'Vou n\'êtes pas inscrit à cette sortie ! ');
            return $this->redirectToRoute('sortie_recherche');
        }

        //Ajout du user en participant de la sortie
        $sorties->removeUser($user);

        $entityManager->persist($sorties);
        $entityManager->flush();

        $this->addFlash('success', 'Vous vous êtes désister de cette sortie !');


        return $this->render('sortie/detail.html.twig', [
            "sorties" => $sorties
        ]);
    }


    /**
     * @Route("/sortie/recherche", name="sortie_recherche")
     *
     */
    public function recherche(EntityManagerInterface $entityManager,
                              EtatRepository $etatRepository,
                              SortieRepository $sortieRepository,
                              Request $request,
                              UpdateEntity $updateEntity): Response
    {
            //maj etat bdd
        $updateEntity->majEtat($sortieRepository->findAll(),$etatRepository->findAll());

            //creation d un model de recherche via formulaire
        $recherche= new Recherche();
        $recherche->setUser($this->getUser());
        $rechercheForm=$this->createForm(RechercheType::class,$recherche);
        $rechercheForm->handleRequest($request);

            //traitement des requetes formulaire dans le repository
        $resultat=$sortieRepository->rechercherSortie($recherche);


            //renvoie a la page d'acceuil avec le formulaire et les resultat du repository
        return $this->render('sortie/recherche.html.twig', [
            'rechercheForm'=>$rechercheForm->createView(),
            'resultat'=>$resultat,

        ]);
    }




}



