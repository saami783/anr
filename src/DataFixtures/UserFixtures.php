<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Enum\UserRoleEnum;

class UserFixtures extends Fixture {
    private $faker;

    public function __construct(private UserPasswordHasherInterface $userPasswordHasher,
                                private EntityManagerInterface $entityManager,)
    {
        $this->faker = Faker\Factory::create();
    }

    public function load(ObjectManager $manager): void {
        $role = null;

        for($i = 0; $i < 10; $i++) {
            if($i % 2 == 0) {
                 $role = UserRoleEnum::ROLE_ADMIN;
            } else {
                $role = UserRoleEnum::ROLE_USER;
            }
            $this->createUser($this->faker->name(), $this->faker->email(), $role);
        }
        $this->entityManager->flush();
    }

    private function createUser(string $name, string $email, string $role): void {
        $user = new User();
        $user->setName($name);
        $user->setEmail($email);
        $user->setCreatedAt(new \DateTimeImmutable());
        $plainPassword = 'Password123!';
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $plainPassword
            )
        );

        $user->setRoles([$role]);
        $this->entityManager->persist($user);
    }
}