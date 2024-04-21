<?php

namespace App\Controller\Admin;

use App\Form\SearchFormType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{

    #[Route('/admin', name: 'app_admin')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $form = $this->createForm(SearchFormType::class);
        $form->handleRequest($request);
        $users = [];

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $users = $userRepository->findByCityOrStreet($data['city'], $data['street']);
        }

        return $this->render('admin/index.html.twig', [
            'form' => $form->createView(),
            'users' => $users,
        ]);
    }

}
