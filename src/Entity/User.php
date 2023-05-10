<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 * @ORM\Table(name="user_account")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ROLE_USER = 'ROLE_USER';
    public const ROLE_CLIENT = 'ROLE_CLIENT';
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $isVerified = false;

    /**
     * @ORM\OneToMany(targetEntity=Workout::class, mappedBy="client", orphanRemoval=true)
     * @ORM\OrderBy({"date" = "ASC"})
     */
    private array|ArrayCollection|PersistentCollection $clientWorkouts;

    /**
     * @var Exercise[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity=Exercise::class, mappedBy="client")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    private array|ArrayCollection|PersistentCollection $exercises;

    public function __construct()
    {
        $this->clientWorkouts = new ArrayCollection();
        $this->exercises = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        $roles[] = self::ROLE_USER;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function isClient(): bool
    {
        return in_array(self::ROLE_CLIENT, $this->getRoles(), true);
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): string
    {
        return $this->getEmail();
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection|Workout[]
     */
    public function getClientWorkouts(): Collection
    {
        return $this->clientWorkouts;
    }

    public function addClientWorkout(Workout $workout): self
    {
        if (!$this->clientWorkouts->contains($workout)) {
            $this->clientWorkouts[] = $workout;
            $workout->setClient($this);
        }

        return $this;
    }

    public function removeClientWorkout(Workout $workout): self
    {
        if ($this->clientWorkouts->removeElement($workout)) {
            // set the owning side to null (unless already changed)
            if ($workout->getClient() === $this) {
                $workout->setClient(null);
            }
        }

        return $this;
    }

    public function getUserIdentifier(): string
    {
        return sprintf(
            '%s %s (%s)',
            $this->getFirstname(),
            $this->getLastname(),
            $this->getEmail()
        );
    }

    public function __toString(): string
    {
        return $this->getUserIdentifier();
    }

    /**
     * @return Collection|Exercise[]
     */
    public function getExercises(): Collection
    {
        return $this->exercises;
    }

    public function addExercise(Exercise $exercise): self
    {
        if (!$this->exercises->contains($exercise)) {
            $this->exercises[] = $exercise;
            $exercise->setClient($this);
        }

        return $this;
    }

    public function removeExercise(Exercise $exercise): self
    {
        if ($this->exercises->removeElement($exercise)) {
            // set the owning side to null (unless already changed)
            if ($exercise->getClient() === $this) {
                $exercise->setClient(null);
            }
        }

        return $this;
    }
}
