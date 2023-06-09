<?php

declare(strict_types=1);

namespace App\Tests\Entity\Builder;

use App\Entity\User;
use App\Entity\Workout;
use Carbon\Carbon;
use DateTime;

class WorkoutBuilder
{
    private DateTime $date;
    private User|null $client;
    private array $components;
    private string $title;
    private string $description;
    private string $warmup;
    private string $status;

    public function __construct()
    {
        $this->date = Carbon::today()->toDate();
        $this->components = [];
        $this->title = 'Pull Workout';
        $this->description = '';
        $this->warmup = '';
        $this->status = Workout::STATUS_PENDING;
    }

    public function withClient(User $client): self
    {
        $this->client = $client;

        return $this;
    }

    public function withDate(DateTime $dateTime): self
    {
        $this->date = $dateTime;

        return $this;
    }

    public function withStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function withTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function build(): Workout
    {
        $workout = new Workout();
        $workout->setDate($this->date);
        $workout->setTitle($this->title);
        $workout->setDescription($this->description);
        $workout->setWarmup($this->warmup);
        $workout->setStatus($this->status);

        foreach ($this->components as $component) {
            $workout->addComponent($component);
        }

        if (null === $this->client) {
            $this->client = (new UserBuilder())->build();
        }

        $this->client->addClientWorkout($workout);

        return $workout;
    }
}
