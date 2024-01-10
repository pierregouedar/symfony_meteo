<?php


namespace App\Tests\Controller\Registration;

use App\Tests\Support\ControllerTester;

class registerCest
{
    public function registrationPageIsUp(ControllerTester $I): void
    {
        $I->amOnPage('/register');
        $I->see("Inscrivez-vous", "h1");
    }
}
