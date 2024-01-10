<?php


namespace App\Tests\Controller\Weather;

use App\Factory\AddressFactory;
use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class indexCest
{
    public function showingFavoriteAddressWeatherAndForecastIsWorking(ControllerTester $I)
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
        $I->amOnPage('/weather/1');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'default');
        $I->click('#login');

        $I->see("Informations instantanées sur l'emplacement :   51000 Châlons-en-Champagne", 'h2');
        $I->see('Liste des prévisions météo sur 7 jours', 'h2');
    }
}
