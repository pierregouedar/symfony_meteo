<?php

namespace App\Controller;

use App\Repository\AddressRepository;
use App\WMOInterpretor;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * Restriction de navigabilité ici, car toutes les actions du contrôleur sont qu'accessibles par des utilisateurs authentifiés.
 */
#[IsGranted('ROLE_ADMIN')]
class WeatherController extends AbstractController
{
    /**
     * @param int $id
     * @param AddressRepository $addressRepository
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * Cette action permet d'afficher la météo instantanée et les prévisions sur 7 jours d'une adresse, qu'elle soit déjà en favoris chez l'utilisateur connecté ou non (Si l'utilisateur choisi de consulter la météo au moment de la recherche).
     */
    #[Route('/weather/{id}', requirements: ['id' => '\d+'])]
    public function index(int $id, AddressRepository $addressRepository, EntityManagerInterface $entityManager, Request $request): Response
    {
        if ($id==0)
        {
            $address=$request->query->get('address');
            $coord=[$address['geometry']['coordinates'][1], $address['geometry']['coordinates'][0]];
            $authorized=true;
            $locationLabel = $address["properties"]["label"];

        }
        else {
        $address = $addressRepository->findById($id);
            if ( $address->getUser()->getId() === $this->getUser()->getId())
            {
                $coord=[$address->getLatitude(), $address->getLongitude()];
                $authorized=true;
                $locationLabel = "{$address->getHouseNumber()} {$address->getStreet()} {$address->getpostcode()} {$address->getcity()}";
            }
        }
        if($authorized)
        {
            $WeatherClient = HttpClient::create();
            $WeatherResponse = $WeatherClient->request('GET', "https://api.open-meteo.com/v1/forecast?latitude={$coord[0]}&longitude={$coord[1]}&current=temperature_2m,relative_humidity_2m,precipitation,weather_code,wind_speed_10m,wind_direction_10m&daily=weather_code,temperature_2m_max,temperature_2m_min,sunrise,sunset,uv_index_max,precipitation_hours,precipitation_probability_max,wind_speed_10m_max,wind_direction_10m_dominant");
            $WeatherContent= $WeatherResponse->toArray();
            $currentWeatherCode=$WeatherContent['current']['weather_code'];
            $forecastWeatherDescriptions =[];
            $forecastWeatherIcon = [];
            foreach ($WeatherContent['daily']['weather_code'] as $code ){
                $forecastWeatherDescriptions[]=WMOInterpretor::getDescription($code);
                $forecastWeatherIcon[]=WMOInterpretor::getIcon($code);
            }
            return $this->render('weather/index.html.twig', ['locationLabel'=>$locationLabel,'weather'=>$WeatherContent, 'forecastWeatherDescriptions'=>$forecastWeatherDescriptions,'currentWeatherDescription'=>WMOInterpretor::getDescription($currentWeatherCode), 'weatherIcon'=> WMOInterpretor::getIcon($WeatherContent['current']['weather_code']), 'forecastWeatherIcon' => $forecastWeatherIcon]);
        }
    }
}
