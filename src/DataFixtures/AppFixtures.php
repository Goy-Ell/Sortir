<?php

namespace App\DataFixtures;

use App\Entity\Etat;
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

        // $product = new Product();
        // $manager->persist($product);


    }
}
