<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use App\Entity\User;
use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $faker = Faker\Factory::create('fr_FR');


        $etatNames=['Créée','Ouverte','Cloturé','Activité en cours','Passée','Annulée'];
        foreach ($etatNames as $name ){
            $etat = new Etat();
            $etat->setLibelle($name);
            $manager->persist($etat);
        }


        $siteNames=['Nantes','Rennes','Quimper','Niort'];
        foreach ($siteNames as $name){
            $site=new Site();
            $site->setNom($name);
            $manager->persist($site);
        }


        for($i=0;$i<=50;$i++){
            $ville=New Ville();
            $ville->setNom($faker->city);
            $ville->setCodePostal($faker->postcode);

            $manager->persist($ville);
        }
        $manager->flush();

        $siteRepo = $manager->getRepository(Site::class);
        $sites = $siteRepo->findAll();


        for($i=0;$i<=50;$i++){
            $user=New User();

            $user->setSite($faker->randomElement($sites)) ;
            $user->setNom($faker->name()) ;
            $user->setRoles(['ROLE_USER']) ;
            $user->setActif($faker->boolean()) ;
            $user->setAdmin($faker->boolean(5)) ;
            $user->setEmail($faker->email) ;
            $user->setTelephone($faker->phoneNumber) ;
            $user->setPseudo($faker->name()) ;
            $user->setPrenom($faker->firstName()) ;
            $user->setPassword($faker->password()) ;

            $manager->persist($user);
        }

        $manager->flush();




        $etatRepo=$manager->getRepository(Etat::class);
        $etats=$etatRepo->findAll();

        $villeRepo=$manager->getRepository(Ville::class);
        $villes=$villeRepo->findAll();

        $userRepo =$manager->getRepository(User::class);
        $users=$userRepo->findAll();


        for($i=0;$i<=50;$i++){
            $lieu=New Lieu();
            $lieu->setNom($faker->text(15) ) ;
            $lieu->setRue($faker->streetName ) ;
            $lieu->setVille($faker->randomElement($villes) ) ;

            $manager->persist($lieu);
        }
        $manager->flush();


        $lieuRepo=$manager->getRepository(Lieu::class);
        $lieux=$lieuRepo->findAll();




        for($i=0;$i<=50;$i++){
            $sortie= New Sortie();
            $sortie->setNom($faker->text(20));
            $sortie->setDateHeureDebut($faker->dateTime()) ;
            $sortie->setDateLimiteInscription($faker->dateTime()) ;
            $sortie->setDuree($faker->numberBetween(60,1000000)) ;
            $sortie->setEtat($faker->randomElement($etats)) ;
            $sortie->setNbInscriptionMax($faker->numberBetween(1,100)) ;
            $sortie->setSite($faker->randomElement($sites)) ;
            $sortie->setOrganisateur($faker->randomElement($users)) ;
            $sortie->setInfosSortie($faker->text(200)) ;
            $sortie->setLieu($faker->randomElement($lieux)) ;

            $manager->persist($sortie);
        }
        $manager->flush();
    }
}
