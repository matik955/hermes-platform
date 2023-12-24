<?php

namespace App\Tests\DataFixtures;

use App\Tests\Factory\AccountFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        AccountFactory::createOne();
    }
}
