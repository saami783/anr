<?php

namespace App\Controller\Admin;

use App\Entity\Street;
use App\Form\StreetFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StreetAdminController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManager)
    {

    }

    #[Route('/admin/creer/rue', name: 'app_admin_street_create')]
    public function create(Request $request): Response {
        $street = new Street();
        $this->denyAccessUnlessGranted('NEW', $street);
        $streetForm = $this->createForm(StreetFormType::class, $street);
        $streetForm->handleRequest($request);

        if($streetForm->isSubmitted() && $streetForm->isValid()) {
            $street->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($street);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_streets');
        }

        return $this->render('admin/street/new.html.twig', [
            'streetForm' => $streetForm->createView(),
        ]);
    }

    #[Route('/admin/rue/{id}/edit', name: 'app_admin_street_update')]
    public function update(Street $street, Request $request): Response {
        $this->denyAccessUnlessGranted('EDIT', $street);
        $streetForm = $this->createForm(StreetFormType::class, $street);
        $streetForm->handleRequest($request);

        if($streetForm->isSubmitted() && $streetForm->isValid()) {
            $street->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($street);
            $this->entityManager->flush();
            return $this->redirectToRoute('app_streets');
        }

        return $this->render('admin/street/update.html.twig', [
            'streetForm' => $streetForm->createView(),
            'street' => $street,
        ]);
    }

    #[Route('/admin/rue/{id}/delete', name: 'app_admin_street_delete', methods: 'POST')]
    public function delete(Street $street, Request $request): Response {
        $this->denyAccessUnlessGranted('DELETE', $street);
        $csrfToken = $request->request->get('_token');
        if($this->isCsrfTokenValid('delete'.$street->getId(), $csrfToken)) {
            $this->addFlash('error', 'Invalid CSRF token');
            return $this->redirectToRoute('app_streets');
        }
        $this->entityManager->remove($street);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_streets');
    }

}
