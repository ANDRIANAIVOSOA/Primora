<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\DataFixtures\AccessCodeFixtures;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
    	$accessCode = $manager->getRepository('App:AccessCode')->findByDescription('P4H');
        $user = new User();
        $user->setUsername('heriniaina');
        $user->setPassword('heriniaina');
        $user->setEmail('r.herriniaina@gmail.com');
        $user->setAccessCode($accessCode[0]);
        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies()
    {
    	return array(
    		AccessCodeFixtures::class
    	);
    }
}
