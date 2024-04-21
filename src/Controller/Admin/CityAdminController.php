<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Form\CityFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CityAdminController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/admin/ville/{id}', name: 'app_admin_city_detail')]
    public function detail(City $city): Response {
        return $this->render('views/admin/city/detail.html.twig', ['city' => $city]);
    }

    #[Route('/admin/creer/ville', name: 'app_admin_city_create')]
    public function create(Request $request): Response {
        $city = new City();

        $cityForm = $this->createForm(CityFormType::class, $city);
        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted() && $cityForm->isValid()) {
            $city->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($city);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_cities');
        }

        return $this->render('views/admin/city/new.html.twig', [
            'cityForm' => $cityForm->createView(),
        ]);
    }

    #[Route('/admin/ville/{id}/update', name: 'app_admin_city_update')]
    public function update(City $city, Request $request): Response {

        $cityForm = $this->createForm(CityFormType::class, $city);
        $cityForm->handleRequest($request);

        if($cityForm->isSubmitted() && $cityForm->isValid()) {
            $city->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($city);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_cities');
        }

        return $this->render('views/admin/city/update.html.twig', [
            'cityForm' => $cityForm->createView(),
            'city' => $city,
        ]);
    }

    #[Route('/admin/ville/{id}/delete', name: 'app_admin_city_delete', methods: 'POST')]
    public function delete(City $city, Request $request): Response {
        $csrfToken = $request->request->get('_token');
        if($this->isCsrfTokenValid('delete'.$city->getId(), $csrfToken)) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('app_cities');
        }
        $this->entityManager->remove($city);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_cities');
    }
}
