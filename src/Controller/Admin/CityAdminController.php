<?php

namespace App\Controller\Admin;

use App\Form\SearchFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CityAdminController extends AbstractController
{

    #[Route('/admin/ville/{city}', name: 'app_admin_city_detail')]
    public function detail(): void { }

    #[Route('/admin/ville/{city}/update', name: 'app_admin_city_update')]
    public function update(): void { }

    #[Route('/admin/ville/{city}/delete', name: 'app_admin_city_delete')]
    public function delete(): void { }
}
