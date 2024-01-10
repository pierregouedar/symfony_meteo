<?php

namespace App\Controller;

use App\WMOInterpretor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class HomeController extends AbstractController
{
    /**
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * Cette action permet d'afficher la page d'accueil contenant la météo instantanée à Reims.
     */
    #[Route("/")]
    public function index(): Response
    {
        $coord=[49.250948, 4.055595];
        $currentWeatherClient = HttpClient::create();
        $currentWeatherResponse = $currentWeatherClient->request('GET', "https://api.open-meteo.com/v1/forecast?latitude={$coord[0]}&longitude={$coord[1]}&current=temperature_2m,relative_humidity_2m,precipitation,weather_code,wind_speed_10m,wind_direction_10m&daily=weather_code,temperature_2m_max,temperature_2m_min,sunrise,sunset,uv_index_max,precipitation_hours,precipitation_probability_max,wind_speed_10m_max,wind_direction_10m_dominant");
        $currentWeatherContent= $currentWeatherResponse->toArray();;
        $weatherDescription = WMOInterpretor::getDescription($currentWeatherContent['current']['weather_code']);
        $weatherIcon = WMOInterpretor::getIcon($currentWeatherContent['current']['weather_code']);
        return $this->render('home/index.html.twig', ['latitude'=>$coord[0], 'longitude'=>$coord[1], 'currentWeatherContent' => $currentWeatherContent, 'weatherDescription'=>$weatherDescription, 'weatherIcon'=>$weatherIcon]);
    }

    /**
     * @return Response
     * Cette action permet d'afficher la page "à propos".
     */
    #[Route("/about")]
    public function about():Response{
        return $this->render('home/about.html.twig');
    }
}
