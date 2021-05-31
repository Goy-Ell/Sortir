<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantController extends AbstractController
{
    /**
     * @Route("/participant/recherche", name="participant_recherche")
     */
    public function recherche(): Response
    {
        return $this->render('participant/recherche.html.twig', [
            'controller_name' => 'ParticipantController',
        ]);
    }
}
