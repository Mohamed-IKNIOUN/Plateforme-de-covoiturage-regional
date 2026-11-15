<?php

namespace App\Entity;

use App\Enum\BookingStatus;
use App\Repository\BookingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $seats = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $totalPrice = null;

    #[ORM\Column(enumType: BookingStatus::class)]
    private ?BookingStatus $status = BookingStatus::PENDING;

    #[ORM\Column]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $cancelledAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $cancellationReason = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $idempotencyKey = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $passenger = null;

    #[ORM\ManyToOne(inversedBy: 'bookings')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TripStop $boardAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?TripStop $alightAt = null;

    #[ORM\OneToOne(mappedBy: 'booking', targetEntity: Payment::class, cascade: ['persist', 'remove'])]
    private ?Payment $payment = null;

    /** @var Collection<int, Review> */
    #[ORM\OneToMany(mappedBy: 'booking', targetEntity: Review::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status    = BookingStatus::PENDING;
        $this->reviews   = new ArrayCollection();
    }

    public function getId(): ?int { return $this->id; }

    public function getSeats(): ?int { return $this->seats; }
    public function setSeats(int $seats): static { $this->seats = $seats; return $this; }

    public function getTotalPrice(): ?string { return $this->totalPrice; }
    public function setTotalPrice(string $p): static { $this->totalPrice = $p; return $this; }

    public function getStatus(): ?BookingStatus { return $this->status; }
    public function setStatus(BookingStatus $s): static { $this->status = $s; return $this; }

    public function getCreatedAt(): ?\DateTime { return $this->createdAt; }
    public function setCreatedAt(\DateTime $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getCancelledAt(): ?\DateTime { return $this->cancelledAt; }
    public function setCancelledAt(?\DateTime $cancelledAt): static { $this->cancelledAt = $cancelledAt; return $this; }

    public function getCancellationReason(): ?string { return $this->cancellationReason; }
    public function setCancellationReason(?string $r): static { $this->cancellationReason = $r; return $this; }

    public function getIdempotencyKey(): ?string { return $this->idempotencyKey; }
    public function setIdempotencyKey(string $k): static { $this->idempotencyKey = $k; return $this; }

    public function getPassenger(): ?User { return $this->passenger; }
    public function setPassenger(?User $u): static { $this->passenger = $u; return $this; }

    public function getTrip(): ?Trip { return $this->trip; }
    public function setTrip(?Trip $t): static { $this->trip = $t; return $this; }

    public function getBoardAt(): ?TripStop { return $this->boardAt; }
    public function setBoardAt(?TripStop $s): static { $this->boardAt = $s; return $this; }

    public function getAlightAt(): ?TripStop { return $this->alightAt; }
    public function setAlightAt(?TripStop $s): static { $this->alightAt = $s; return $this; }

    public function getPayment(): ?Payment { return $this->payment; }
    public function setPayment(?Payment $p): static
    {
        if ($p === null && $this->payment !== null) {
            $this->payment->setBooking(null);
        }
        if ($p !== null && $p->getBooking() !== $this) {
            $p->setBooking($this);
        }
        $this->payment = $p;
        return $this;
    }

    /** @return Collection<int, Review> */
    public function getReviews(): Collection { return $this->reviews; }
    public function addReview(Review $r): static
    {
        if (!$this->reviews->contains($r)) {
            $this->reviews->add($r);
            $r->setBooking($this);
        }
        return $this;
    }
    public function removeReview(Review $r): static
    {
        if ($this->reviews->removeElement($r)) {
            if ($r->getBooking() === $this) {
                $r->setBooking(null);
            }
        }
        return $this;
    }
}