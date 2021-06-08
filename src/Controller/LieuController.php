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
     * Fonction pour requete AJAX de recherche des lieux selon la ville
     * @Route("/lieu/recherche", name="lieu_recherche")
     *
     */
    public function rechercheLieu(LieuRepository $lieuRepository, Request $request):Response
    {
        $ville = $request->query->get('ville');
        dump("rechercheLieu : ".$ville);
        $resultats = $lieuRepository->rechercheLieuSelonVille($ville);

        return $this->render("sortie/ajax_lieux.html.twig", [
            "lieux"=>$resultats
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
        return $this->render("sortie/ajax_detailsLieu.html.twig", [
            "lieu"=>$resultat
        ]);
    }
}
