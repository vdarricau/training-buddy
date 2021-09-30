<?php

namespace App\Entity;

use App\Repository\ExerciseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;

/**
 * @ORM\Entity(repositoryClass=ExerciseRepository::class)
 */
class Exercise
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="exercises")
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity=Component::class, mappedBy="exercise")
     * @ORM\OrderBy({"id" = "DESC"})
     */
    private $components;

    public function __construct()
    {
        $this->components = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    #[Pure]
    public function __toString(): string
    {
        return $this->getName();
    }

    public function getClient(): ?User
    {
        return $this->client;
    }

    public function setClient(?User $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|Component[]
     */
    public function getComponents(): Collection
    {
        return $this->components;
    }

    public function addComponent(Component $component): self
    {
        if (!$this->components->contains($component)) {
            $this->components[] = $component;
            $component->setExercise($this);
        }

        return $this;
    }

    public function removeComponent(Component $component): self
    {
        if ($this->components->removeElement($component)) {
            // set the owning side to null (unless already changed)
            if ($component->getExercise() === $this) {
                $component->setExercise(null);
            }
        }

        return $this;
    }
}
