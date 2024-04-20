<?php

namespace App\Controller\User;

use App\Entity\Address;
use App\Entity\City;
use App\Entity\User;
use App\Repository\StreetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AddressController extends AbstractController
{

    #[Route('/delete-address/{id}', name: 'app_delete_address')]
    public function deleteAddress(Address $address, EntityManagerInterface $entityManager): Response {
        if ($this->getUser() === $address->getUser()) {
            $entityManager->remove($address);
            $entityManager->flush();
            $this->addFlash('success', 'Adresse supprimée avec succès.');
        } else {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer une adresse qui ne vous appartient pas.');
        }
        return $this->redirectToRoute('app_profil');
    }

    /**
     * Retourne une liste de rues pour une ville donnée au format JSON.
     *
     * @param City $cityId L'objet City identifié par l'ID passé en paramètre dans l'URL.
     * @param StreetRepository $streetRepository Le dépôt utilisé pour interroger les rues.
     *
     * Cette route est appelée via AJAX lorsqu'un utilisateur sélectionne une ville dans un formulaire.
     * Elle extrait les rues associées à cette ville et renvoie les données au format JSON,
     * où chaque rue est représentée par son ID et son nom.
     *
     * @return JsonResponse Liste des rues de la ville spécifiée en format JSON.
     */
    #[Route('/get-streets/{cityId}', name: 'get_streets_by_city')]
    public function getStreetsByCity(City $cityId, StreetRepository $streetRepository): JsonResponse
    {
        $streets = $streetRepository->findBy(['city' => $cityId]);
        return $this->json([
            'streets' => array_map(function ($street) {
                return ['id' => $street->getId(), 'name' => $street->getName()];
            }, $streets)
        ]);
    }
}
