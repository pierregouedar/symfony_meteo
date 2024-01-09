<?php

namespace App\Controller;

use App\Entity\Address;
use App\Repository\AddressRepository;
use App\WMOInterpretor;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    #[Route('/address/find')]
    public function find(Request $request): Response
    {
        $get = '';
        if (null !== $request->query->get('search')) {
            $get = $request->query->get('search');
        }
        if ($get != ''){
            if(strlen($get)>=3){
                $AddressHttpClient = HttpClient::create();
                $AddressResponse = $AddressHttpClient->request('GET', "https://api-adresse.data.gouv.fr/search/?q={$get}");
                $AddressContent= $AddressResponse->toArray();
                return $this->render('address/index.html.twig', [
                    'search' => $get,
                    'addresses' => $AddressContent,
                ]);
            }
            else{
                return $this->redirectToRoute("app_home_index");
            }
        }

    }
    #[Route('/address/add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $get = $request->query->get('address');
        $address = new Address();
        $address->setLatitude($get['geometry']['coordinates'][1]);
        $address->setLongitude($get['geometry']['coordinates'][0]);
        $address->setCity($get['properties']['city']);
        $address->setPostcode($get['properties']['postcode']);
        if(isset($get['properties']['housenumber'])){
            $address->setHouseNumber($get['properties']['housenumber']);
        }
        if(isset($get['properties']['street'])){
            $address->setStreet($get['properties']['street']);
        }
        $address->setType($get['properties']['type']);
        $address->setUser($this->getUser());
        $entityManager->persist($address);
        $entityManager->flush();
        return $this->render('address/show.html.twig', ['addresses'=>$this->getUser()->getAddresses()]); // à changer
    }

    #[Route('/address')]
    public function show():Response{
        $addresses = $this->getUser()->getAddresses();
        return $this->render('address/show.html.twig', ['addresses' => $addresses]);
    }

    #[Route('/address/{id}/remove', requirements: ['id' => '\d+'])]
    public function remove($id, AddressRepository $addressRepository, EntityManagerInterface $entityManager): Response
    {
        $address= $addressRepository->findById($id);
        if($address->getUser()->getId() === $this->getUser()->getId()){
            $entityManager->remove($address);
            $entityManager->flush();
            return $this->redirectToRoute("app_address_show");
        }
        else {
            return $this->redirectToRoute("app_address_show");
        }
    }
}
