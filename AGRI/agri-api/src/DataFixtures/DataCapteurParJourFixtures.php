<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\DataCapteurParJour;
use App\DataFixtures\AccessCodeFixtures;
use App\DataFixtures\SiteFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DataCapteurParJourFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        echo "data par jour";
        $accessCode = $manager->getRepository('App:AccessCode')->findByDescription('P4H');
        $device = $manager->getRepository('App:Device')->find('DEVICE-1');
        $site = $manager->getRepository('App:Site')->findOneBySiteName('Antsirabe');
        $capteur = $manager->getRepository('App:Capteur')->findByName('HAFANANA');

        for ($i=0; $i < 5; $i++) { 
            $dataCapteur = new DataCapteurParJour();
            $dataCapteur->setSendingDateTime(new \Datetime('2018-12-17 15:20:00'));
            $dataCapteur->setFreshData(new \Datetime('2018-12-17 15:20:00'));
            $dataCapteur->setLevel($i);
            $dataCapteur->setDeviceId($device);
            $dataCapteur->setSiteId($site->getId());
            $dataCapteur->setCapteur($capteur[0]);
            $manager->persist($dataCapteur);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
    	return array(
    		AccessCodeFixtures::class,
            SiteFixtures::class,
            DeviceFixtures::class,
            CapteurFixtures::class
        );
    }
}
