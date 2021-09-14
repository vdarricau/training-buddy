<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Tests\Entity\Builder\WorkoutBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WorkoutFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        /** @var User $client */
        $client = $this->getReference(ClientFixtures::CLIENT_EMAIL);
        $workout = (new WorkoutBuilder())
            ->withClient($client)
            ->build()
        ;

        $client->addClientWorkout($workout);
        $manager->persist($client);
        $manager->persist($workout);

        $manager->flush();
    }
}
