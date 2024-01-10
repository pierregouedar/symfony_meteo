<?php


namespace App\Tests\Controller\Address;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class findCest
{
    public function showsResultsWhenQueryIsReims(ControllerTester $I): void
    {
        UserFactory::createOne([
            'firstname' => 'Tony',
            'lastname' => 'Stark',
            'email' => 'root@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $I->amOnPage('/address/find?search=reims');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'default');
        $I->click('#login');

        $I->seeCurrentRouteIs('app_address_find');
        $I->amOnPage('/address/find?search=reims');

        $I->see('Liste des résultats pour la recherche : "reims"', 'h2');

    }
}
