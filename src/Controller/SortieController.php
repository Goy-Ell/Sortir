<?php

namespace App\Controller;


use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\User;
use App\Form\LieuType;
use App\Form\RechercheType;
use App\Form\SortieType;
use App\Model\Recherche;
use App\Repository\EtatRepository;
use App\Repository\LieuRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(Request $request,
                            EntityManagerInterface $entityManager,
                            EtatRepository $etatRepository
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


        $entityManager->persist($sortie);
        $entityManager->flush(); 

            $this->addFlash('success', 'Sortie ajoutée ! ');

            return $this->redirectToRoute('sortie_recherche');
        }


        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

    /**
     * @Route("/lieu/create", name="lieu_create")
     */

    public function ajouterLieu(Request $request,
                                EntityManagerInterface $entityManager):Response
    {
        $lieu = new Lieu();

        $lieuForm = $this->createForm(LieuType::class, $lieu);

        $lieuForm->handleRequest($request);

        if($lieuForm->isSubmitted() && $lieuForm->isValid()){

            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu ajouté !');

            return $this->redirectToRoute('sortie_create');

        }

        return $this->render('lieu/create.html.twig', [
            'lieuForm' => $lieuForm->createView()
        ]);
    }

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



    /**
     * @Route("/sortie/recherche", name="sortie_recherche")
     * @var   User $user
     */
    public function recherche(SortieRepository $sortieRepository, Request $request): Response
    {

//      $this->getUser()->getSite();

        $recherche= new Recherche();
        $recherche->setUser($this->getUser());
        $rechercheForm=$this->createForm(RechercheType::class,$recherche);
        $rechercheForm->handleRequest($request);

        $resultat=$sortieRepository->rechercherSortie($recherche);


        return $this->render('sortie/recherche.html.twig', [
            'rechercheForm'=>$rechercheForm->createView(),
            'resultat'=>$resultat,

        ]);
    }

    /**
     * @Route("/sortie/ajax-lieu", name="sortie_ajax_lieu")
     *
     */
    public function remplissageLieu(Request $request,
                                    LieuRepository $lieuRepository,
                                    EntityManagerInterface $entityManager):Response
    {
        $data = json_decode($request->getContent(), true);

        $lieu = $data->rue;
        $lieu_id = $data ->lieu;

        $rue = $lieuRepository->find($lieu_id);

        return new JsonResponse('rue');


    }
}



