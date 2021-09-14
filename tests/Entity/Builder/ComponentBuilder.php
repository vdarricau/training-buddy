<?php

declare(strict_types=1);

namespace App\Tests\Entity\Builder;

use App\Entity\Component;
use App\Entity\Workout;

class ComponentBuilder
{
    private string $title;
    private string $setAndRep;
    private int $orderNumber;
    private Workout|null $workout;

    private static int $currentOrderNumber = 0;

    public function __construct()
    {
        $this->title = 'Test title';
        $this->setAndRep = 'Set and rep';
        $this->orderNumber = self::$currentOrderNumber++;
    }

    public function withWorkout(Workout $workout): self
    {
        $this->workout = $workout;

        return $this;
    }

    public function build(): Component
    {
        $component = new Component();
        $component->setTitle($this->title);
        $component->setSetAndRep($this->setAndRep);
        $component->setOrderNumber($this->orderNumber);

        if (null === $this->workout) {
            $this->workout = (new WorkoutBuilder())->build();
        }

        $component->setWorkout($this->workout);

        return $component;
    }
}
