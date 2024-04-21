<?php

namespace App\Controller\User;

use App\Entity\Address;
use App\Entity\Avatar;
use App\Entity\User;
use App\Form\AddressFormType;
use App\Form\AvatarFormType;
use App\Form\ChangePasswordFormType;
use App\Form\InfoUserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProfilController extends AbstractController
{

    public function __construct(private UserRepository $userRepository,
                                private EntityManagerInterface $entityManager,
                                )
    {

    }

    /**
     * Cette fonction gère les modifications des données utilisateur.
     * @param Request $request
     * @return Response
     */
    #[Route('/profil', name: 'app_profil')]
    public function index(Request $request): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        $avatar = $user->getAvatar() ?? new Avatar();
        $address = $user->getAddress() ?? new Address();

        $infoForm = $this->createForm(InfoUserFormType::class, $user);
        $passwordForm = $this->createForm(ChangePasswordFormType::class, $user);
        $avatarForm = $this->createForm(AvatarFormType::class, $avatar);
        $addressForm = $this->createForm(AddressFormType::class, $address);

        $infoForm->handleRequest($request);
        $passwordForm->handleRequest($request);
        $avatarForm->handleRequest($request);
        $addressForm->handleRequest($request);

        $changesMade = false;
        if ($infoForm->isSubmitted() && $infoForm->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->userRepository->save($user, true);
            $changesMade = true;
        }
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $newPlainTextPassword = $passwordForm->get('plainPassword')->getData();
            $user->setUpdatedAt(new \DateTimeImmutable());
            $this->userRepository->upgradePassword($user, $newPlainTextPassword);
            $changesMade = true;
        }
        if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {
            if ($avatar->getUser() === null) {
                $avatar->setUser($user);
            }
            $avatar->getUser()->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($avatar);
            $changesMade = true;
        }
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $address->setStreet($addressForm->get('street')->getData());
            $address->setUser($this->getUser());
            $address->getUser()->setUpdatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($address);
            $changesMade = true;
        }

        if ($changesMade) {
            $this->entityManager->flush();
            $this->addFlash('success', 'Les modifications ont bien été apportées.');
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('user/profil/index.html.twig', [
            'infoForm' => $infoForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'avatarForm' => $avatarForm->createView(),
            'addressForm' => $addressForm->createView(),
            'address' => $address,
            'avatar' => $avatar
        ]);
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    #[Route('/delete', name: 'app_profil_delete')]
    public function delete() : Response {

        /** @var User $user */
        $user = $this->getUser();

        // Déconnecte l'utilisateur avant de le supprimer.
        $this->container->get('security.token_storage')->setToken(null);

        $this->userRepository->remove($user, true);

        return $this->redirectToRoute('app_home');
    }


}
