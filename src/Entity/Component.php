<?php

namespace App\Entity;

use App\Repository\ComponentRepository;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use LogicException;

/**
 * @ORM\Entity(repositoryClass=ComponentRepository::class)
 */
class Component
{
    public const STATUS_PENDING = 'pending';
    public const STATUS_DONE = 'done';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_DONE,
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $setAndRep;

    /**
     * @ORM\Column(type="integer")
     */
    private $orderNumber = 0;

    /**
     * @ORM\ManyToOne(targetEntity=Workout::class, inversedBy="components")
     * @ORM\JoinColumn(nullable=false)
     */
    private $workout;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status = self::STATUS_PENDING;

    /**
     * @ORM\ManyToOne(targetEntity=Variation::class)
     */
    private $variation;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSetAndRep(): ?string
    {
        return $this->setAndRep;
    }

    public function setSetAndRep(?string $setAndRep): self
    {
        $this->setAndRep = $setAndRep;

        return $this;
    }

    public function getOrderNumber(): ?int
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(int $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getWorkout(): Workout
    {
        return $this->workout;
    }

    public function setWorkout(Workout $workout): self
    {
        $this->workout = $workout;

        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        if (false === in_array($status, self::STATUSES, true)) {
            throw new LogicException('Status `%s` is not supported');
        }

        $this->status = $status;

        return $this;
    }

    public function getVariation(): ?Variation
    {
        return $this->variation;
    }

    public function setVariation(?Variation $variation): self
    {
        $this->variation = $variation;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    #[Pure]
    public function __toString(): string
    {
        return $this->getTitle();
    }
}
