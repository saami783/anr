<?php

namespace App\Controller;

use App\Repository\StreetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StreetController extends AbstractController
{
    #[Route('/rues', name: 'app_streets')]
    public function index(StreetRepository $streetRepository): Response
    {
        return $this->render('views/public/street/index.html.twig', [
            'streets' => $streetRepository->findAll(),
        ]);
    }
}
