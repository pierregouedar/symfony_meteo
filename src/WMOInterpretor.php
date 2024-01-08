<?php

namespace App;

class WMOInterpretor
{
    #Permet d'obtenir une description de la météo à partir du code WMO retourné par l'API
    public static function getDescription($code) {
        $descriptions = [
            0 => "Ciel dégagé",
            1 => "Principalement dégagé",
            2 => "Partiellement nuageux",
            3 => "Couvert",
            45 => "Brouillard",
            48 => "Brouillard givrant",
            51 => "Bruine légère",
            53 => "Bruine modérée",
            55 => "Bruine intense",
            56 => "Bruine givrante légère",
            57 => "Bruine givrante intense",
            61 => "Pluie légère",
            63 => "Pluie modérée",
            65 => "Pluie intense",
            66 => "Pluie givrante légère",
            67 => "Pluie givrante intense",
            71 => "Neige légère",
            73 => "Neige modérée",
            75 => "Neige intense",
            77 => "Grains de neige",
            80 => "Averses de pluie légères",
            81 => "Averses de pluie modérées",
            82 => "Averses de pluie violentes",
            85 => "Averses de neige légères",
            86 => "Averses de neige violentes",
            95 => "Orage: légèrement ou modérément intense",
            96 => "Orage avec grêle légèrement intense",
            99 => "Orage avec grêle violent",
        ];

        return $descriptions[$code];
    }
}