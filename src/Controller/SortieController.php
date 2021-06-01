<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\RechercheType;
use App\Form\SortieType;
use App\Model\Recherche;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/create", name="sortie_create")
     */
    public function create(Request $request,
                            EntityManagerInterface $entityManager
                            ): Response
    {
        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);

        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

        $entityManager->persist($sortie);
        $entityManager->flush();

            $this->addFlash('success', 'Sortie ajoutée ! ');

            return $this->redirectToRoute('sortie_create');
        }


        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView()
        ]);
    }

    /**
     * @Route("/sortie/recherche", name="sortie_recherche")
     */
    public function recherche(SortieRepository $sortieRepository, Request $request): Response
    {
        $recherche= new Recherche();
        $rechercheForm=$this->createForm(RechercheType::class,$recherche);
        $rechercheForm->handleRequest($request);

        $resultat=$sortieRepository->rechercherSortie($recherche);

//        $nbSorties=$participantRepository->count([]);

        return $this->render('sortie/recherche.html.twig', [
            'rechercheForm'=>$rechercheForm->createView(),
            'resultat'=>$resultat,

        ]);
    }
}
