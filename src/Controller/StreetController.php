<?php

namespace App\Controller;

use App\Entity\Street;
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

    #[Route('/rue/{id}', name: 'app_admin_street_detail')]
    public function detail(Street $street): Response {
        return $this->render('views/public/street/detail.html.twig', [
            'street' => $street
        ]);
    }
}
