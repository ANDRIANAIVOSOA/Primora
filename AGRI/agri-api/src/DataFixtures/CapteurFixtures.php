<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Capteur;
use App\DataFixtures\AccessCodeFixtures;
use App\DataFixtures\SiteFixtures;

class CapteurFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $accessCode = $manager->getRepository('App:AccessCode')->findByDescription('P4H');
        $capteur = new Capteur();
        $capteur->setName('HAFANANA');
        $capteur->setIconFull('icon full');
        $capteur->setIconClear('icon clear');
        $capteur->setUnite('degre');
        $capteur->setType(2002);
        $capteur->setAccessCode($accessCode[0]);
        $manager->persist($capteur);

        $capteur2 = new Capteur();
        $capteur2->setName('ORANA');
        $capteur2->setIconFull('icon full');
        $capteur2->setIconClear('icon clear');
        $capteur2->setUnite('degre');
        $capteur2->setType(2002);
        $capteur2->setAccessCode($accessCode[0]);
        $manager->persist($capteur2);

        $manager->flush();
    }

    public function getDependencies()
    {
    	return array(
    		AccessCodeFixtures::class
        );
    }
}
