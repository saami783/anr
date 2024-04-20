<?php

namespace App\Controller\user;

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

        if ($infoForm->isSubmitted() && $infoForm->isValid()) {
            $this->userRepository->save($user, true);
        }
        if ($passwordForm->isSubmitted() && $passwordForm->isValid()) {
            $newPlainTextPassword = $passwordForm->get('plainPassword')->getData();
            $this->userRepository->upgradePassword($user, $newPlainTextPassword);
        }
        if ($avatarForm->isSubmitted() && $avatarForm->isValid()) {
            $this->entityManager->persist($avatar);
        }
        if ($addressForm->isSubmitted() && $addressForm->isValid()) {
            $this->entityManager->persist($address);
        }

        if ($infoForm->isSubmitted() || $passwordForm->isSubmitted() ||
            $avatarForm->isSubmitted() || $addressForm->isSubmitted())
        {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_profil');
        }

        return $this->render('views/user/profil/index.html.twig', [
            'infoForm' => $infoForm->createView(),
            'passwordForm' => $passwordForm->createView(),
            'avatarForm' => $avatarForm->createView(),
            'addressForm' => $addressForm->createView(),
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
