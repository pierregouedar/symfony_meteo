<?php


namespace App\Tests\Controller\Address;

use App\Factory\AddressFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class removeCest
{
    public function removingAnAddressIsSuccessful(ControllerTester $I): void
    {
        AddressFactory::createOne([
            'latitude'=>48.95355,
            'longitude'=>4.365717,
            'city'=>"Châlons-en-Champagne",
            'postcode'=>51000,
            'type'=>"municipality",
            'user'=>UserFactory::createOne([
                'firstname' => 'Tony',
                'lastname' => 'Stark',
                'email' => 'root@example.com',
                'roles' => ['ROLE_ADMIN'],
            ])
        ]);
        $I->amOnPage('/address/');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'default');
        $I->click('#login');

        $I->see('Vos emplacement favoris :', 'h1');
        $I->see('Municipalité :    51000 Châlons-en-Champagne', 'h5');

        $I->amOnPage('/address/1/remove');

        $I->seeCurrentRouteIs('app_address_show');
        $I->amOnPage('/address');
        $I->see('Vos emplacement favoris :', 'h1');
        $I->dontSee('Municipalité :    51000 Châlons-en-Champagne', 'h5');
    }
}
