<?php

declare(strict_types=1);

namespace App\Tests\Entity\Builder;

use App\Entity\User;

class UserBuilder
{
    private string $firstname;
    private string $lastname;
    private string $email;
    private array $roles;
    private array $clientWorkouts;
    private bool $isVerified;
    private string $password;

    public function __construct()
    {
        $this->firstname = 'Valou';
        $this->lastname = 'Darricoach';
        $this->email = 'valou@trainingbuddy.com';
        $this->roles = [];
        $this->clientWorkouts = [];
        $this->isVerified = true;
        $this->password = 'mitra123';
    }

    public function withEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function withRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function build(): User
    {
        $user = new User();
        $user->setFirstname($this->firstname);
        $user->setLastname($this->lastname);
        $user->setEmail($this->email);
        $user->setRoles($this->roles);
        $user->setIsVerified($this->isVerified);
        $user->setPassword($this->password);

        foreach ($this->clientWorkouts as $clientWorkout) {
            $user->addClientWorkout($clientWorkout);
        }

        return $user;
    }
}
