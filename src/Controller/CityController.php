<?php

namespace App\Controller;

use App\Entity\City;
use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CityController extends AbstractController
{
    #[Route('/villes', name: 'app_cities')]
    public function index(CityRepository $cityRepository): Response
    {
        return $this->render('public/city/index.html.twig', [
            'cities' => $cityRepository->findAll(),
        ]);
    }

    #[Route('/ville/{id}', name: 'app_admin_city_detail')]
    public function detail(City $city): Response {
        return $this->render('public/city/detail.html.twig', ['city' => $city]);
    }
}
