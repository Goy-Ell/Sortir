<?php

namespace App\Controller;

use App\Form\MonProfilType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class UserController extends AbstractController
{
    /**
     * Affiche le profil de l'utilisateur et permet de la modifier si besoin
     * @Route("/user/profil/{id}", name="user_profil")
     */
    public function profil($id,
                           UserRepository $userRepository,
                           EntityManagerInterface $entityManager,
                           UserPasswordEncoderInterface $passwordEncoder,
                           Request $request,
                           GuardAuthenticatorHandler $guardHandler,
                           AppAuthenticator $authenticator): Response
    {
        //instancie l'utilisateur connecté
        $user = $userRepository->find($id);
        //verification si l'utilisateur est bien recuperer
        if (!$user){
            throw $this->createNotFoundException("Utilisateur non trouvé !!");
        }

        $monProfilType = $this->createForm(MonProfilType::class, $user);
        $user->setRoles(["ROLE_USER"]);
        $user->setActif(true);
        $user->setAdmin(false);
        $monProfilType->handleRequest($request);

        if ($monProfilType->isSubmitted() && $monProfilType->isValid()){

            //encodage du password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $monProfilType->get('password')->getData()
                )
            );

            /**
             * Gestion du telechargement de la photo de profil
             * @var  UploadedFile file
             */
            $file = $monProfilType->get('photoProfil')->getData();
            if ($file){
                $directory = $this->getParameter('upload_photo_profil_dir');
                //rename files - parametre uniqid special prdi nico !!
                $newFileName = $user->getNom().'-'.uniqid('', true).'.'.$file->guessExtension();
                //save in to directory (+ modified service.yaml)
                $file->move($directory, $newFileName);
                //save name in BDD
                $user->setPhotoProfil($newFileName);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Modifications enregistrées !! ');

            //permet de rester connecté après modification du profil
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('user/profil.html.twig', [
            'monProfilType'=>$monProfilType->createView()
        ]);
    }
}
