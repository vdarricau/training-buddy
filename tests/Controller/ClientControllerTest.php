<?php

declare(strict_types=1);

namespace App\Tests\Controller;

use App\DataFixtures\ClientFixtures;
use App\Entity\User;
use App\Entity\Workout;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientControllerTest extends WebTestCase
{
    private EntityManagerInterface|null $entityManager;
    private KernelBrowser $symfonyClient;
    private User $client;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->symfonyClient = self::createClient();

        $this->entityManager = self::$kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $this->client = $this->entityManager
            ->getRepository(User::class)
            ->findOneByEmail(ClientFixtures::CLIENT_EMAIL);
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
    public function client_should_access_his_workout_list(): void
    {
        $this->symfonyClient->loginUser($this->client);

        $this->symfonyClient->request('GET', '/client');
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('a.btn.btn-lg.btn-primary', 'Create Workout');
    }

    /**
     * @test
     */
    public function client_should_view_his_workout(): void
    {
        /** @var Workout $workout */
        $workout = $this->client->getClientWorkouts()->first();

        $this->symfonyClient->loginUser($this->client);

        $this->symfonyClient->request('GET', sprintf('/client/workout/view/%s', $workout->getId()));
        self::assertResponseIsSuccessful();
        self::assertSelectorTextContains('a.btn.btn-lg.btn-danger', 'Back to workouts');
        self::assertSelectorTextContains('h1', (string)$workout);
    }

    /**
     * @test
     */
    public function client_should_edit_his_workout(): void
    {
        /** @var Workout $workout */
        $workout = $this->client->getClientWorkouts()->first();

        $this->symfonyClient->loginUser($this->client);

        $this->symfonyClient->request('GET', sprintf('/client/workout/edit/%s', $workout->getId()));

        self::assertResponseIsSuccessful();

        $this->symfonyClient->submitForm('Submit', [
            'workout_form[title]' => 'A brand new title',
        ]);

        self::assertResponseRedirects(sprintf('/client/workout/view/%s', $workout->getId()));

        $this->symfonyClient->followRedirect();

        self::assertSelectorTextContains(
            'h1',
            sprintf('A brand new title - %s', $workout->getDate()->format('Y-m-d'))
        );
    }
}
