<?php

namespace App\DataFixtures;

use App\Entity\Birthday;
use App\Factory\BirthdayFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class BirthdayFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        BirthdayFactory::new()->createMany(30);

        $manager->flush();
    }
}
