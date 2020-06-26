<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Device;
use App\DataFixtures\AccessCodeFixtures;
use App\DataFixtures\SiteFixtures;

class DeviceFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $accessCode = $manager->getRepository('App:AccessCode')->findByDescription('P4H');
    	$site = $manager->getRepository('App:Site')->findOneBySiteName('Antsirabe');
        $device = new Device();
        $device->setId('DEVICE-1');
        $device->setSite($site);
        $device->setAccessCode($accessCode[0]);
        $device->setAgentName('RABOKONA');
        $manager->persist($device);
        $manager->flush();
    }

    public function getDependencies()
    {
    	return array(
    		AccessCodeFixtures::class,
            SiteFixtures::class
    	);
    }
}
