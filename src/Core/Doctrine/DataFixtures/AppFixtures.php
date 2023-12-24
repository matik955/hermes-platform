<?php

namespace App\Core\Doctrine\DataFixtures;

use App\Tests\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\ORMFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements ORMFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        UserFactory::createOne();
    }
}
