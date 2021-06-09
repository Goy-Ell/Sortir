<?php

namespace App\Controller;

use App\Entity\Site;
use App\Form\SiteType;
use App\Repository\SiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SiteController extends AbstractController
{
    /**
     * @Route("/site/create", name="site_create")
     */
     public function ajouterSite(Request $request,
                                 EntityManagerInterface $entityManager):Response
     {
         $site = new Site();

         $siteForm = $this->createForm(SiteType::class, $site);

         $siteForm->handleRequest($request);

         if($siteForm->isSubmitted() && $siteForm->isValid()){

             $entityManager->persist($site);
             $entityManager->flush();

             $this->addFlash('success', 'Site ajouté !');

             return $this->redirectToRoute('sortie_recherche');

         }

         return $this->render('site/create.html.twig', [
             'siteForm' => $siteForm->createView(),
             'page'=> 1
         ]);
     }

    /**
     * Affiche l'ensemble des sites en ADMIN
     * @Route("/site/ensembleSite", name="site_ensembleSite")
     */
    public function ensembleSite(SiteRepository $siteRepository){

        $sites = $siteRepository->findAll();

        return $this->render('site/ensembleSite.html.twig', [
            'sites'=>$sites
        ]);
    }


}
