<?php

declare(strict_types=1);

namespace App\Tests\Security\Voter;

use App\Security\Voter\WorkoutVoter;
use App\Tests\Entity\Builder\UserBuilder;
use App\Tests\Entity\Builder\WorkoutBuilder;
use App\Tests\Security\FakeToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class WorkoutVoterTest extends KernelTestCase
{
    private EntityManagerInterface|null $entityManager;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
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
    public function should_allow_client_owning_the_workout_to_edit_it(): void
    {
        $user = (new UserBuilder())->build();

        $fakeToken = new FakeToken();
        $fakeToken->setUser($user);

        $workout = (new WorkoutBuilder())
            ->withClient($user)
            ->build()
        ;

        $this->entityManager->persist($user);
        $this->entityManager->persist($workout);
        $this->entityManager->flush();

        self::assertSame(
            VoterInterface::ACCESS_GRANTED,
            (new WorkoutVoter())->vote($fakeToken, $workout, [WorkoutVoter::EDIT])
        );
    }

    /**
     * @test
     */
    public function should_allow_client_owning_the_workout_to_view_it(): void
    {
        $user = (new UserBuilder())->build();

        $fakeToken = new FakeToken();
        $fakeToken->setUser($user);

        $workout = (new WorkoutBuilder())
            ->withClient($user)
            ->build()
        ;

        $this->entityManager->persist($user);
        $this->entityManager->persist($workout);
        $this->entityManager->flush();

        self::assertSame(
            VoterInterface::ACCESS_GRANTED,
            (new WorkoutVoter())->vote($fakeToken, $workout, [WorkoutVoter::VIEW])
        );
    }

    /**
     * @test
     */
    public function should_not_allow_client_to_edit_other_client_workout(): void
    {
        $client = (new UserBuilder())->build();
        $anotherClient = (new UserBuilder())->build();

        $fakeToken = new FakeToken();
        $fakeToken->setUser($client);

        $workout = (new WorkoutBuilder())
            ->withClient($anotherClient)
            ->build()
        ;

        $this->entityManager->persist($client);
        $this->entityManager->persist($anotherClient);
        $this->entityManager->persist($workout);
        $this->entityManager->flush();

        self::assertSame(
            VoterInterface::ACCESS_DENIED,
            (new WorkoutVoter())->vote($fakeToken, $workout, [WorkoutVoter::EDIT])
        );
    }

    /**
     * @test
     */
    public function should_not_allow_client_to_view_other_client_workout(): void
    {
        $client = (new UserBuilder())->build();
        $anotherClient = (new UserBuilder())->build();

        $fakeToken = new FakeToken();
        $fakeToken->setUser($client);

        $workout = (new WorkoutBuilder())
            ->withClient($anotherClient)
            ->build()
        ;

        $this->entityManager->persist($client);
        $this->entityManager->persist($anotherClient);
        $this->entityManager->persist($workout);
        $this->entityManager->flush();

        self::assertSame(
            VoterInterface::ACCESS_DENIED,
            (new WorkoutVoter())->vote($fakeToken, $workout, [WorkoutVoter::VIEW])
        );
    }
}
