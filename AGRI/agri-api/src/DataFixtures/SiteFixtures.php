<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Site;
use App\DataFixtures\AccessCodeFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class SiteFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        echo "site";
    	$accessCode = $manager->getRepository('App:AccessCode')->findByDescription('P4H');
        $site = new Site();
        $site->setSiteName('Antsirabe');
        $site->setAccessCode($accessCode[0]);
        $manager->persist($site);
        $manager->flush();
    }

    public function getDependencies()
    {
    	return array(
    		AccessCodeFixtures::class
    	);
    }
}
