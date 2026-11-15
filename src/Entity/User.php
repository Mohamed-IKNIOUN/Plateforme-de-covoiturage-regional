<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Vehicle::class, orphanRemoval: true)]
    private Collection $vehicles;

    #[ORM\OneToMany(mappedBy: 'driver', targetEntity: Trip::class)]
    private Collection $tripsAsDriver;

    #[ORM\OneToMany(mappedBy: 'passenger', targetEntity: Booking::class)]
    private Collection $bookings;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Review::class)]
    private Collection $writtenReviews;

    #[ORM\OneToMany(mappedBy: 'target', targetEntity: Review::class)]
    private Collection $receivedReviews;

    public function __construct()
    {
        $this->vehicles        = new ArrayCollection();
        $this->tripsAsDriver   = new ArrayCollection();
        $this->bookings        = new ArrayCollection();
        $this->writtenReviews  = new ArrayCollection();
        $this->receivedReviews = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int { return $this->id; }

    public function getEmail(): ?string { return $this->email; }
    public function setEmail(string $email): static { $this->email = $email; return $this; }

    public function getUserIdentifier(): string { return (string) $this->email; }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }
    public function setRoles(array $roles): static { $this->roles = $roles; return $this; }

    public function getPassword(): ?string { return $this->password; }
    public function setPassword(string $password): static { $this->password = $password; return $this; }

    public function eraseCredentials(): void {}

    public function getFirstName(): ?string { return $this->firstName; }
    public function setFirstName(string $firstName): static { $this->firstName = $firstName; return $this; }

    public function getLastName(): ?string { return $this->lastName; }
    public function setLastName(string $lastName): static { $this->lastName = $lastName; return $this; }

    public function getPhone(): ?string { return $this->phone; }
    public function setPhone(?string $phone): static { $this->phone = $phone; return $this; }

    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }
    /** @return Collection<int, Vehicle> */
    public function getVehicles(): Collection { return $this->vehicles; }
    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicles->contains($vehicle)) {
            $this->vehicles->add($vehicle);
            $vehicle->setOwner($this);
        }
        return $this;
    }
    public function removeVehicle(Vehicle $vehicle): static
    {
        if ($this->vehicles->removeElement($vehicle)) {
            if ($vehicle->getOwner() === $this) {
                $vehicle->setOwner(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Trip> */
    public function getTripsAsDriver(): Collection { return $this->tripsAsDriver; }
    public function addTripsAsDriver(Trip $trip): static
    {
        if (!$this->tripsAsDriver->contains($trip)) {
            $this->tripsAsDriver->add($trip);
            $trip->setDriver($this);
        }
        return $this;
    }
    public function removeTripsAsDriver(Trip $trip): static
    {
        if ($this->tripsAsDriver->removeElement($trip)) {
            if ($trip->getDriver() === $this) {
                $trip->setDriver(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Booking> */
    public function getBookings(): Collection { return $this->bookings; }
    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setPassenger($this);
        }
        return $this;
    }
    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            if ($booking->getPassenger() === $this) {
                $booking->setPassenger(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Review> */
    public function getWrittenReviews(): Collection { return $this->writtenReviews; }
    public function addWrittenReview(Review $review): static
    {
        if (!$this->writtenReviews->contains($review)) {
            $this->writtenReviews->add($review);
            $review->setAuthor($this);
        }
        return $this;
    }
    public function removeWrittenReview(Review $review): static
    {
        if ($this->writtenReviews->removeElement($review)) {
            if ($review->getAuthor() === $this) {
                $review->setAuthor(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, Review> */
    public function getReceivedReviews(): Collection { return $this->receivedReviews; }
    public function addReceivedReview(Review $review): static
    {
        if (!$this->receivedReviews->contains($review)) {
            $this->receivedReviews->add($review);
            $review->setTarget($this);
        }
        return $this;
    }
    public function removeReceivedReview(Review $review): static
    {
        if ($this->receivedReviews->removeElement($review)) {
            if ($review->getTarget() === $this) {
                $review->setTarget(null);
            }
        }
        return $this;
    }
}