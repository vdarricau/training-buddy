<?php

declare(strict_types=1);

namespace App\Tests\Repository;

use App\Entity\Workout;
use App\Repository\WorkoutRepository;
use App\Tests\Entity\Builder\UserBuilder;
use App\Tests\Entity\Builder\WorkoutBuilder;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class WorkoutRepositoryTest extends KernelTestCase
{
    private WorkoutRepository $workoutRepository;
    private EntityManagerInterface|null $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        $this->workoutRepository = $this->entityManager
            ->getRepository(Workout::class)
        ;
    }

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
    public function should_return_past_workouts(): void
    {
        $user = (new UserBuilder())->build();
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        self::assertEmpty($this->workoutRepository->findPastWorkouts($user));

        $upcomingWorkout = (new WorkoutBuilder())
            ->withDate(Carbon::tomorrow()->toDate())
            ->withClient($user)
            ->build()
        ;

        $this->entityManager->persist($upcomingWorkout);
        $this->entityManager->flush();

        self::assertEmpty($this->workoutRepository->findPastWorkouts($user));

        $expectedPastWorkout = (new WorkoutBuilder())
            ->withDate(Carbon::yesterday()->toDate())
            ->withClient($user)
            ->build()
        ;

        $this->entityManager->persist($expectedPastWorkout);
        $this->entityManager->flush();

        $pastWorkouts = $this->workoutRepository->findPastWorkouts($user);
        self::assertCount(1, $pastWorkouts);
        self::assertSame($expectedPastWorkout->getId(), $pastWorkouts[0]->getId());
    }

    /**
     * @test
     */
    public function should_return_upcoming_workouts(): void
    {
        $user = (new UserBuilder())->build();
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        self::assertEmpty($this->workoutRepository->findUpcomingWorkouts($user));

        $expectedUpcomingWorkout = (new WorkoutBuilder())
            ->withDate(Carbon::tomorrow()->toDate())
            ->withClient($user)
            ->build()
        ;

        $this->entityManager->persist($expectedUpcomingWorkout);
        $this->entityManager->flush();

        $upcomingWorkouts = $this->workoutRepository->findUpcomingWorkouts($user);
        self::assertCount(1, $upcomingWorkouts);
        self::assertSame($expectedUpcomingWorkout->getId(), $upcomingWorkouts[0]->getId());

        $pastWorkout = (new WorkoutBuilder())
            ->withDate(Carbon::yesterday()->toDate())
            ->withClient($user)
            ->build()
        ;

        $this->entityManager->persist($pastWorkout);
        $this->entityManager->flush();

        self::assertCount(1, $this->workoutRepository->findUpcomingWorkouts($user));
    }
}
