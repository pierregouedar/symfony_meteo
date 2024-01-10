<?php


namespace App\Tests\Controller\Address;

use App\Factory\UserFactory;
use App\Tests\Support\ControllerTester;

class AddCest
{
    public function addingReimsAsMunicipalityInDB(ControllerTester $I): void
    {
        UserFactory::createOne([
            'firstname' => 'Tony',
            'lastname' => 'Stark',
            'email' => 'root@example.com',
            'roles' => ['ROLE_ADMIN'],
        ]);

        $I->amOnPage('/address/add?address%5Btype%5D=Feature&address%5Bgeometry%5D%5Btype%5D=Point&address%5Bgeometry%5D%5Bcoordinates%5D%5B0%5D=4.055595&address%5Bgeometry%5D%5Bcoordinates%5D%5B1%5D=49.250948&address%5Bproperties%5D%5Blabel%5D=Reims&address%5Bproperties%5D%5Bscore%5D=0.95910818181818&address%5Bproperties%5D%5Bid%5D=51454&address%5Bproperties%5D%5Btype%5D=municipality&address%5Bproperties%5D%5Bname%5D=Reims&address%5Bproperties%5D%5Bpostcode%5D=51100&address%5Bproperties%5D%5Bcitycode%5D=51454&address%5Bproperties%5D%5Bx%5D=776864.36&address%5Bproperties%5D%5By%5D=6906211.75&address%5Bproperties%5D%5Bpopulation%5D=180318&address%5Bproperties%5D%5Bcity%5D=Reims&address%5Bproperties%5D%5Bcontext%5D=51,%20Marne,%20Grand%20Est&address%5Bproperties%5D%5Bimportance%5D=0.55019&address%5Bproperties%5D%5Bmunicipality%5D=Reims');
        $I->fillField('email', 'root@example.com');
        $I->fillField('password', 'default');
        $I->click('#login');

        $I->seeCurrentRouteIs('app_address_add');
        $I->amOnPage('/address');
        $I->see('Vos emplacement favoris :', 'h1');
        $I->see('Municipalité :    51100 Reims', 'h5');

    }
}
