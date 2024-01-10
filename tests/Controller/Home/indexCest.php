<?php


namespace App\Tests\Controller\Home;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class indexCest
{
    public function homePageIsWorkingAndDisplayingLoginButton(ControllerTester $I): void
    {
        $I->amOnPage('/');
        $I->see("Connectez-vous", "a");
    }

    public function homePageIsWorkingAndDisplayingSearchBarWhenLoggedIn(ControllerTester $I): void
    {
        UserFactory::createOne([
            'firstname' => 'Tony',
            'lastname' => 'Stark',
            'email' => 'root@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $I->amOnPage('/login');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'default');
        $I->click('#login');
        $I->amOnPage('/');
        $I->seeElement("form");
    }
}
