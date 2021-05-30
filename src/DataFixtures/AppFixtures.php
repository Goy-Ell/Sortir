<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Site;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $etatNames=['Créée','Ouverte','Cloturé','Activité en cours','Passée','Annulée'];
        foreach ($etatNames as $name ){
            $etat = new Etat();
            $etat->setLibelle($name);
            $manager->persist($etat);
        }
        $manager->flush();


        $siteNames=['Nantes','Rennes','Quimper','Niort'];
        foreach ($siteNames as $name){
            $site=new Site();
            $site->setNom($name);
            $manager->persist($site);
        }
        $manager->flush();


    }
}
