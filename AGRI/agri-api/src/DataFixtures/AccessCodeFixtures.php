<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\AccessCode;

class AccessCodeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $ac = new AccessCode();
        $ac->setDescription('P4H');
        $manager->persist($ac);
        $manager->flush();
    }
}
