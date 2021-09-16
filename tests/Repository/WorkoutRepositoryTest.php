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

        $this->workoutRepository = $this->entityManager
            ->getRepository(Workout::class)
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
    public function should_seperate_workouts_either_finished_or_upcoming(): void
    {
        $user = (new UserBuilder())->build();
        $this->entityManager->persist($user);
        $this->entityManager->flush($user);

        self::assertEmpty($this->workoutRepository->findPastOrFinishedWorkouts($user));
        self::assertEmpty($this->workoutRepository->findUpcomingWorkouts($user));

        $tomorrowsWorkout = (new WorkoutBuilder())
            ->withTitle('tomorrow')
            ->withDate(Carbon::tomorrow()->toDate())
            ->withClient($user)
            ->build()
        ;

        $todayButNotStarted = (new WorkoutBuilder())
            ->withTitle('todayButNotStarted')
            ->withDate(Carbon::today()->toDate())
            ->withClient($user)
            ->withStatus(Workout::STATUS_PENDING)
            ->build()
        ;

        $todayButFinished = (new WorkoutBuilder())
            ->withTitle('todayButFinished')
            ->withDate(Carbon::today()->toDate())
            ->withClient($user)
            ->withStatus(Workout::STATUS_FINISHED)
            ->build()
        ;

        $yesterdaysWorkout = (new WorkoutBuilder())
            ->withTitle('yesterday')
            ->withDate(Carbon::yesterday()->toDate())
            ->withClient($user)
            ->build()
        ;

        $this->entityManager->persist($tomorrowsWorkout);
        $this->entityManager->persist($todayButFinished);
        $this->entityManager->persist($todayButNotStarted);
        $this->entityManager->persist($yesterdaysWorkout);
        $this->entityManager->flush();

        $pastWorkouts = $this->workoutRepository->findPastOrFinishedWorkouts($user);
        self::assertCount(2, $pastWorkouts);
        self::assertSame($todayButFinished->getId(), reset($pastWorkouts)->getId());
        self::assertSame($yesterdaysWorkout->getId(), next($pastWorkouts)->getId());

        $upcomingWorkouts = $this->workoutRepository->findUpcomingWorkouts($user);

        self::assertCount(2, $upcomingWorkouts);
        self::assertSame($todayButNotStarted->getId(), reset($upcomingWorkouts)->getId());
        self::assertSame($tomorrowsWorkout->getId(), next($upcomingWorkouts)->getId());
    }
}
