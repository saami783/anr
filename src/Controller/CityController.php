<?php

namespace App\Controller;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CityController extends AbstractController
{
    #[Route('/villes', name: 'app_cities')]
    public function index(CityRepository $cityRepository): Response
    {
        return $this->render('views/public/city/index.html.twig', [
            'cities' => $cityRepository->findAll(),
        ]);
    }
}
