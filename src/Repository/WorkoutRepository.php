<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Workout;
use Carbon\Carbon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Workout|null find($id, $lockMode = null, $lockVersion = null)
 * @method Workout|null findOneBy(array $criteria, array $orderBy = null)
 * @method Workout[]    findAll()
 * @method Workout[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WorkoutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Workout::class);
    }

    /**
     * @param User $client
     * @return Workout[]
     */
    public function findPastOrFinishedWorkouts(User $client): array
    {
        $query = $this->createQueryBuilder('workout')
            ->andWhere('workout.client = :client')
            ->andWhere('workout.date < :today OR workout.status = :finished')
            ->setParameters([
                'client' => $client,
                'today' => Carbon::today(),
                'finished' => Workout::STATUS_FINISHED,
            ])
            ->orderBy('workout.date', 'DESC')
            ->getQuery()
        ;

        return $query->getResult();
    }

    /**
     * @param User $client
     * @return Workout[]
     */
    public function findUpcomingWorkouts(User $client): array
    {
        $query = $this->createQueryBuilder('workout')
            ->andWhere('workout.client = :client')
            ->andWhere('workout.date >= :today')
            ->andWhere('workout.status != :finished')
            ->setParameters([
                'client' => $client,
                'today' => Carbon::today(),
                'finished' => Workout::STATUS_FINISHED,
            ])
            ->orderBy('workout.date')
            ->getQuery()
        ;

        return $query->getResult();
    }
}
