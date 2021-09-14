<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Tests\Entity\Builder\UserBuilder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ClientFixtures extends Fixture
{
    public const CLIENT_EMAIL = 'fixtures@trainingbuddy.com';

    public function load(ObjectManager $manager): void
    {
        $client = (new UserBuilder())
            ->withEmail(self::CLIENT_EMAIL)
            ->withRoles([User::ROLE_CLIENT])
            ->build()
        ;

        $manager->persist($client);

        $manager->flush();
    }
}
