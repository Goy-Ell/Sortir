<?php

namespace App\Controller;

use App\Form\RechercheType;
use App\Model\Recherche;
use App\Repository\ParticipantRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant/recherche", name="participant_recherche")
     */
    public function recherche(): Response
    {
/**        $recherche= new Recherche();
        $rechercheForm=$this->createForm(RechercheType::class,$recherche);
        $rechercheForm->handleRequest($request);
        $resultat=$participantRepository->rechercherSortie($recherche);
*/
//        $nbSorties=$participantRepository->count([]);

        return $this->render('participant/recherche.html.twig', [
//                'rechercheForm'=>$rechercheForm->createView(),
//                'resultat'=>$resultat,

        ]);
    }
}
