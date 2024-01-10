<?php

namespace App\Controller;

use App\Entity\Address;
use App\Repository\AddressRepository;
use App\WMOInterpretor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\Request;
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
class AddressController extends AbstractController
{

    /**
     * @param Request $request
     * @return Response
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * Cette action permet à partir de paramètres passés dans la requête HTTP depuis la barre de recherche d'afficher tous les emplacements qui peuvent correspondre grâce à l'API https://adresse.data.gouv.fr/.
     */
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

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * Cette action permet d'ajouter un emplacement retourné par l'API https://adresse.data.gouv.fr/ dans la address de la base de données.
     */
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

    /**
     * @return Response
     * Cette action permet d'afficher toutes les adresses favorites de l'utilisateur connecté.
     */
    #[Route('/address')]
    public function show():Response{
        $addresses = $this->getUser()->getAddresses();
        return $this->render('address/show.html.twig', ['addresses' => $addresses]);
    }

    /**
     * @param $id
     * @param AddressRepository $addressRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * Cette action permet de supprimer une adresse favorites parmis celle de l'utilisateur connecté.
     */
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
