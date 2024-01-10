<?php


namespace App\Tests\Controller\Address;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class showCest
{
    public function showsFavoritesAddressesWhenLoggedIn(ControllerTester $I): void
    {
        UserFactory::createOne([
            'firstname' => 'Tony',
            'lastname' => 'Stark',
            'email' => 'root@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $I->amOnPage('/address');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'default');
        $I->click('#login');

        $I->seeCurrentRouteIs('app_address_show');
        $I->amOnPage('/address');

        $I->see("Vos emplacement favoris :", 'h1');

    }
}
