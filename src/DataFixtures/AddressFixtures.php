<?php

namespace App\DataFixtures;

use App\Factory\AddressFactory;
use App\Factory\UserFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AddressFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     * @return void
     * Cette fixture permet de créer automatiquement une adresse avec son utilisateur (administrateur) lié.
     */
    public function load(ObjectManager $manager): void
    {
        AddressFactory::createOne([
            'latitude'=>48.95355,
            'longitude'=>4.365717,
            'city'=>"Châlons-en-Champagne",
            'postcode'=>51000,
            'housenumber'=>"2b",
            'street'=>"Rue de Jessaint",
            'type'=>"housenumber",
            'user'=>UserFactory::createOne([
                'firstname' => 'Tony',
                'lastname' => 'Stark',
                'email' => 'root@example.com',
                'roles' => ['ROLE_ADMIN'],
            ])
            ]);
    }
}
