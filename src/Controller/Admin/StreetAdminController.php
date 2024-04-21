<?php

namespace App\Controller\Admin;

use App\Form\SearchFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StreetAdminController extends AbstractController
{

    #[Route('/admin/rue/{street}', name: 'app_admin_street_detail')]
    public function detail(): void { }

    #[Route('/admin/creer/rue', name: 'app_admin_street_create')]
    public function create() { }

    #[Route('/admin/rue/{street}/update', name: 'app_admin_street_update')]
    public function update(): void { }

    #[Route('/admin/rue/{street}/delete', name: 'app_admin_street_delete')]
    public function delete(): void { }

}
