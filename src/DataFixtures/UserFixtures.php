<?php

namespace App\DataFixtures;

use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     * Cette fixture permet de créer un utilisateur automatiquement.
     */
    public function load(ObjectManager $manager): void
    {
        UserFactory::createOne([
            'firstname' => 'Peter',
            'lastname' => 'Parker',
            'email' => 'user@example.com',
            'roles' => ['ROLE_USER'],
        ]);
    }
}
