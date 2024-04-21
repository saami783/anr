<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{

    public function __construct(private AuthorizationCheckerInterface $authorizationChecker,
                                private UrlGeneratorInterface         $urlGenerator)
    {

    }

    #[Route('/register', name: 'app_register')]
    public function register(Request                    $request, UserPasswordHasherInterface $userPasswordHasher,
                             UserAuthenticatorInterface $userAuthenticator, AppCustomAuthenticator $authenticator,
                             EntityManagerInterface     $entityManager): Response
    {

        // Si l'utilisateur est authentifié, je le redirige vers une route en fonction de son rôle.
        if ($this->getUser()) {
            if ($this->authorizationChecker->isGranted('ROLE_USER')) {
                return new RedirectResponse($this->urlGenerator->generate('app_home'));
            } else if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
                return new RedirectResponse($this->urlGenerator->generate('app_home'));
            }
        }

        $user = new User();

        /**
         * L'inscription inclut le formulaire lié à l'adresse d'un utilisateur. Afin d'éviter les problèmes de jeton CSRF,
         * j'ai ajouté dans le RegistrationFormType les champs nécessaires pour créer une adresse. Ils ne sont pas mappé
         * à l'utilisateur.
         */
        $registrationForm = $this->createForm(RegistrationFormType::class, $user);
        $registrationForm->handleRequest($request);

        if ($registrationForm->isSubmitted() && $registrationForm->isValid()) {

            $password = $registrationForm->get('password')->getData();
            $user->setPassword($userPasswordHasher->hashPassword($user, $password));
            $user->setCreatedAt(new \DateTimeImmutable());
            $address = new Address();
            $address->setStreet($registrationForm->get('street')->getData());
            $address->setNumber($registrationForm->get('number')->getData());
            $address->setUser($user);

            $entityManager->persist($user);
            $entityManager->persist($address);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('views/public/registration/register.html.twig', [
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

}
