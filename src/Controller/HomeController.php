<?php

namespace App\Controller;

use App\WMOInterpretor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route("/")]
    public function index(): Response
    {
        $coord=[49.250948, 4.055595];
        $currentWeatherClient = HttpClient::create();
        $currentWeatherResponse = $currentWeatherClient->request('GET', "https://api.open-meteo.com/v1/forecast?latitude={$coord[0]}&longitude={$coord[1]}&current=temperature_2m,relative_humidity_2m,precipitation,weather_code,wind_speed_10m,wind_direction_10m&daily=weather_code,temperature_2m_max,temperature_2m_min,sunrise,sunset");
        $currentWeatherContent= $currentWeatherResponse->toArray();;
        $weatherDescription = WMOInterpretor::getDescription($currentWeatherContent['current']['weather_code']);
        return $this->render('home/index.html.twig', ['latitude'=>$coord[0], 'longitude'=>$coord[1], 'currentWeatherContent' => $currentWeatherContent, 'weatherDescription'=>$weatherDescription]);
    }
}
