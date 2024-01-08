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

    public static function getIcon($code){
        $descriptions = [
            0 => '<span class="material-symbols-outlined">sunny</span>',
            1 => '<span class="material-symbols-outlined">partly_cloudy_day</span>',
            2 => '<span class="material-symbols-outlined">partly_cloudy_day</span>',
            3 => '<span class="material-symbols-outlined">cloud</span>',
            45 => '<span class="material-symbols-outlined">foggy</span>',
            48 => '<span class="material-symbols-outlined">foggy</span><span class="material-symbols-outlined">severe_cold</span>',
            51 => '<span class="material-symbols-outlined">rainy_light</span>',
            53 => '<span class="material-symbols-outlined">rainy_light</span>',
            55 => '<span class="material-symbols-outlined">rainy_heavy</span>',
            56 => '<span class="material-symbols-outlined">rainy_light</span><span class="material-symbols-outlined">severe_cold</span>',
            57 => '<span class="material-symbols-outlined">rainy_heavy</span><span class="material-symbols-outlined">severe_cold</span>',
            61 => '<span class="material-symbols-outlined">rainy</span>',
            63 => '<span class="material-symbols-outlined">rainy</span>',
            65 => '<span class="material-symbols-outlined">rainy</span>',
            66 => '<span class="material-symbols-outlined">rainy</span><span class="material-symbols-outlined">severe_cold</span>',
            67 => '<span class="material-symbols-outlined">rainy</span><span class="material-symbols-outlined">severe_cold</span>',
            71 => '<span class="material-symbols-outlined">snowing</span><span class="material-symbols-outlined">weather_snowy</span>',
            73 => '<span class="material-symbols-outlined">snowing</span><span class="material-symbols-outlined">weather_snowy</span>',
            75 => '<span class="material-symbols-outlined">snowing_heavy</span><span class="material-symbols-outlined">weather_snowy</span>',
            77 => '<span class="material-symbols-outlined">grain</span><span class="material-symbols-outlined">weather_snowy</span>',
            80 => '<span class="material-symbols-outlined">rainy</span><span class="material-symbols-outlined">schedule</span>',
            81 => '<span class="material-symbols-outlined">rainy</span><span class="material-symbols-outlined">schedule</span>',
            82 => '<span class="material-symbols-outlined">rainy</span><span class="material-symbols-outlined">schedule</span>',
            85 => '<span class="material-symbols-outlined">weather_snowy</span><span class="material-symbols-outlined">schedule</span>',
            86 => '<span class="material-symbols-outlined">weather_snowy</span><span class="material-symbols-outlined">schedule</span>',
            95 => '<span class="material-symbols-outlined">thunderstorm</span>',
            96 => '<span class="material-symbols-outlined">thunderstorm</span><span class="material-symbols-outlined">weather_hail</span>',
            99 => '<span class="material-symbols-outlined">thunderstorm</span><span class="material-symbols-outlined">weather_hail</span>',
        ];
        return $descriptions[$code];
    }
}