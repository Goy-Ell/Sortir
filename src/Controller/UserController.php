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
        $user->setActif(true);

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
                //rename files - parametre uniqid special ordi nico !!
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

    /**
     * Affiche le profil d'un utilisateur
     * @Route("/user/profilUtilisateur/{id}", name="user_profilUtilisateur")
     */
    public function profilUtilisateur($id, UserRepository $userRepository){

        $user = $userRepository->find($id);

        return $this->render('user/profilUtilisateur.html.twig', [
            'user'=>$user,
            'page'=> 1
        ]);
    }

    /**
     * Affiche l'ensemble des utilisateurs en ADMIN
     * @Route("/user/ensembleUtilisateur", name="user_ensembleUtilisateur")
     */
    public function ensembleUtilisateur(UserRepository $userRepository){

        $users = $userRepository->findAll();

        return $this->render('user/ensembleUtilisateur.html.twig', [
            'users'=>$users
        ]);
    }

    /**
     * Activer ou désactiver un utilisateur en ADMIN
     * @Route("/user/activerDesactiver/{id}", name="user_activerDesactiver")
     */
    public function activerDesactiver($id, UserRepository $userRepository, EntityManagerInterface $entityManager){

        $user = $userRepository->find($id);

        if ($user->getActif()){
            //desactive un user
            $user->setActif(false);
        } else {
            //active un user
            $user->setActif(true);
        }
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur mis à jour');

        return $this->render('user/profilUtilisateur.html.twig', [
            'user'=>$user,
            'page'=> 2
        ]);
    }

    /**
     * Supprimer un utilisateur en ADMIN
     * @Route("/user/suprrimerUtilisateur/{id}", name="user_suprrimerUtilisateur")
     */
    public function supprimerUtilisateur($id, UserRepository $userRepository, EntityManagerInterface $entityManager){

        $user = $userRepository->find($id);
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'Utilisateur supprimé !');

        return $this->redirectToRoute('sortie_recherche');
    }

    /**
     * Fonction pour requete AJAX
     * Recherche utilisateur selon un champs de saisi
     * @Route("/user/rechercheParNomPrenomPseudo", name="user_rechercheParNomPrenomPseudo")
     */
    public function rechercheParNomPrenomPseudo(UserRepository $userRepository, Request $request):Response
    {
        dump("userController");
        $saisi = $request->query->get('saisi');
        dump($saisi);
        $users = $userRepository->findByNomPrenomPseudo($saisi);

        return $this->render("user/ajax_ensembleUtilisateurs.html.twig", [
            'users'=>$users
        ]);
    }
}
