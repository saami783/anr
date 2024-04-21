<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Form\SearchFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CityRepository;

class CityAdminController extends AbstractController
{

    public function __construct(private CityRepository $cityRepository,
                                private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/admin/ville/{id}', name: 'app_admin_city_detail')]
    public function detail(City $city): Response {
        return $this->render('views/admin/city/detail.html.twig', ['city' => $city]);
    }

    #[Route('/admin/ville/rue', name: 'app_admin_city_create')]
    public function create() { }

    #[Route('/admin/ville/{city}/update', name: 'app_admin_city_update')]
    public function update(): void { }

    #[Route('/admin/ville/{city}/delete', name: 'app_admin_city_delete')]
    public function delete(): void { }
}
