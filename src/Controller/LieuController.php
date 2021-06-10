<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu/create", name="lieu_create")
     */

    public function ajouterLieu(Request $request,
                                EntityManagerInterface $entityManager,
                                VilleRepository $villeRepository):Response
    {
        $lieu = new Lieu();

        $lieuForm = $this->createForm(LieuType::class, $lieu);

        $lieuForm->handleRequest($request);
//        dd($lieu);
        if($lieuForm->isSubmitted() && $lieuForm->isValid()){
//            dd($lieu);

//            recupere la ville le cp la latitude et longitude en dehors du LieuType.php
            $villeId = $_REQUEST['villeSelect'];
            dump($villeId);
            $ville = $villeRepository->find($villeId);
            dump($ville);
            $lieu->setVille($ville);

            $latitude = $_REQUEST['latSelect'];
            $lieu->setLatitude($latitude);
            dump($latitude);
            $longitude = $_REQUEST['lonSelect'];
            $lieu->setLongitude($longitude);
            dd($lieu);
            $entityManager->persist($lieu);
            $entityManager->flush();

            $this->addFlash('success', 'Lieu ajouté !');

            return $this->redirectToRoute('sortie_create');

        }

        return $this->render('lieu/create.html.twig', [
            'lieuForm' => $lieuForm->createView(),
            'page'=>1
        ]);
    }

    /**
     * Fonction pour requete AJAX de recherche d'une ville
     * @Route("/lieu/rechercheVille", name="lieu_rechercheVille")
     *
     */
    public function rechercheVille(VilleRepository $villeRepository, Request $request):Response
    {
        $saisi = $request->query->get('saisi');
        $resultats = $villeRepository->rechercheVilleParSaisi($saisi);
        return $this->render("sortie/ajax_ville.html.twig", [
            "villes"=>$resultats
        ]);

    }


    /**
     * Fonction pour requete AJAX de recherche d'un lieu
     * @Route("/lieu/detailsLieu", name="lieu_detailsLieu")
     */
    public function detailsLieu(LieuRepository $lieuRepository, Request $request):Response
    {
        $lieu = $request->query->get('lieu');

        $resultat = $lieuRepository->find($lieu);
        dump($resultat);
        return $this->render("sortie/ajax_detailsLieu.html.twig", [
            "lieu"=>$resultat
        ]);
    }

    /**
     * Fonction pour requete AJAX de recherche des lieux selon la ville
     * @Route("/lieu/recherche", name="lieu_recherche")
     *
     */
    public function rechercheLieu(LieuRepository $lieuRepository, Request $request):Response
    {
        $ville = $request->query->get('ville');
        $resultats = $lieuRepository->rechercheLieuSelonVille($ville);
        return $this->render("sortie/ajax_lieux.html.twig", [
            "lieux"=>$resultats
        ]);

    }
    /**
     * Fonction pour requete AJAX de recherche les infos de la ville
     * @Route("/lieu/rechercheInfo", name="lieu_rechercheInfo")
     *
     */
    public function rechercheInfo(VilleRepository $villeRepository, Request $request):Response
    {

        $villeId = $request->query->get('ville');
//        dump($villeId);
        $ville = $villeRepository->find($villeId);
//        dd($ville);
        return $this->render("sortie/ajax_info.html.twig", [
            "ville"=>$ville
        ]);

    }


//    /**
//     * Fonction pour requete AJAX de recherche la longitude selon la ville
//     * @Route("/lieu/rechercheLon", name="lieu_rechercheLon")
//     *
//     */
//    public function rechercheLon(VilleRepository $villeRepository, Request $request):Response
//    {
//        $ville = $request->query->get('ville');
//        $resultats = $villeRepository->rechercheVille($ville);
//        return $this->render("sortie/ajax_lon.html.twig", [
//            "villes"=>$resultats
//        ]);
//
//    }
//
//    /**
//     * Fonction pour requete AJAX de recherche la latitude selon la ville
//     * @Route("/lieu/rechercheLat", name="lieu_rechercheLat")
//     *
//     */
//    public function rechercheLat(VilleRepository $villeRepository, Request $request):Response
//    {
//        $ville = $request->query->get('ville');
//        $resultats = $villeRepository->rechercheVille($ville);
//        return $this->render("sortie/ajax_cp.html.twig", [
//            "villes"=>$resultats
//        ]);
//
//    }


}
