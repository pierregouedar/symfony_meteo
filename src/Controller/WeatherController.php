<?php

namespace App\Controller;

use App\Repository\AddressRepository;
use App\WMOInterpretor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WeatherController extends AbstractController
{
    #[Route('/weather/{id}', requirements: ['id' => '\d+'])]
    public function index($id, AddressRepository $addressRepository, EntityManagerInterface $entityManager): Response
    {
        $address= $addressRepository->findById($id);
        if ( $address->getUser()->getId() === $this->getUser()->getId())
        {
            $coord=[$address->getLatitude(), $address->getLongitude()];
            $WeatherClient = HttpClient::create();
            $WeatherResponse = $WeatherClient->request('GET', "https://api.open-meteo.com/v1/forecast?latitude={$coord[0]}&longitude={$coord[1]}&current=temperature_2m,relative_humidity_2m,precipitation,weather_code,wind_speed_10m,wind_direction_10m&daily=weather_code,temperature_2m_max,temperature_2m_min,sunrise,sunset,uv_index_max,precipitation_hours,precipitation_probability_max,wind_speed_10m_max,wind_direction_10m_dominant");
            $WeatherContent= $WeatherResponse->toArray();
            $locationLabel = "{$address->getHouseNumber()} {$address->getStreet()} {$address->getpostcode()} {$address->getcity()}";
            dump($WeatherContent);
            $currentWeatherCode=$WeatherContent['current']['weather_code'];
            $forecastWeatherDescriptions =[];
            foreach ($WeatherContent['daily']['weather_code'] as $code ){
                $forecastWeatherDescriptions[]=WMOInterpretor::getDescription($code);
            }
            return $this->render('weather/index.html.twig', ['locationLabel'=>$locationLabel,'weather'=>$WeatherContent, 'forecastWeatherDescriptions'=>$forecastWeatherDescriptions,'currentWeatherDescription'=>WMOInterpretor::getDescription($currentWeatherCode)]);
        }
    }
}
