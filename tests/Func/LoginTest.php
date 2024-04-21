<?php

namespace App\Tests\Func;

use App\Kernel;
use App\Repository\UserRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Enum\UserRoleEnum;

class LoginTest extends WebTestCase
{
    /**
     * @TODO Intégrer les tests func
     */

    private $client;
    private UserRepository $userRepository;

    protected function setUp(): void {
        $this->client = static::createClient();
        $this->userRepository = static::getContainer()->get(UserRepository::class);
    }

    protected static function getKernelClass(): string {
        return Kernel::class;
    }


    /**
     * @throws NonUniqueResultException
     */
    public function testLogin() : void {
        $this->client->request('GET', '/login');

        $user = $this->userRepository->findByRole(UserRoleEnum::ROLE_USER);

        $this->client->loginUser($user);
        $this->client->followRedirect();
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('title', 'Accueil');

    }
}