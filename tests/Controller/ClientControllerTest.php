<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\ClientFixtures;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientControllerTest extends WebTestCase
{
    private EntityManagerInterface|null $entityManager;
    private KernelBrowser $symfonyClient;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->symfonyClient = self::createClient();

        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;
    }

    /**
     * @inheritDoc
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        // doing this is recommended to avoid memory leaks
        $this->entityManager->close();
        $this->entityManager = null;
    }

    /**
     * @test
     */
    public function client_should_access_to_his_workouts(): void
    {
        $client = $this->entityManager
            ->getRepository(User::class)
            ->findOneByEmail(ClientFixtures::CLIENT_EMAIL);

        $this->symfonyClient->loginUser($client);

        $this->symfonyClient->request('GET', '/client');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('a.btn.btn-lg.btn-primary', 'Create Workout');
    }
}
