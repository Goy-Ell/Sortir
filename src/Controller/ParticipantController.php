<?php

namespace App\Controller;

use App\Entity\Recherche;

use App\Form\RechercheType;

use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant/recherche", name="participant_recherche")
     */
    public function recherche(SortieRepository $sortieRepository, Request $request): Response
    {
        $recherche= new Recherche();
        $rechercheForm=$this->createForm(RechercheType::class,$recherche);
        $rechercheForm->handleRequest($request);
        $resultat=$sortieRepository->rechercherSortie($recherche);

//        $nbSorties=$participantRepository->count([]);

        return $this->render('participant/recherche.html.twig', [
                'rechercheForm'=>$rechercheForm->createView(),
                'resultat'=>$resultat,

        ]);
    }
}
