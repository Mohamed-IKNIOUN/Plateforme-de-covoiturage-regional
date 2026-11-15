<?php

namespace App\Entity;

use App\Enum\TripStatus;
use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $departureAt = null;

    #[ORM\Column]
    private ?int $totalSeats = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 4)]
    private ?string $pricePerKm = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $suggestedPrice = null;

    #[ORM\Column(nullable: true)]
    private ?float $distanceKm = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $routeGeometry = null;

    #[ORM\Column(enumType: TripStatus::class)]
    private ?TripStatus $status = TripStatus::PUBLISHED;

    #[ORM\Version]
    #[ORM\Column(type: 'integer')]
    private ?int $version = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $completedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $cancelledAt = null;

    #[ORM\ManyToOne(inversedBy: 'tripsAsDriver')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $driver = null;

    #[ORM\ManyToOne(inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Vehicle $vehicle = null;

    /** @var Collection<int, TripStop> */
    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: TripStop::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[ORM\OrderBy(['position' => 'ASC'])]
    private Collection $stops;

    /** @var Collection<int, SegmentOccupancy> */
    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: SegmentOccupancy::class, cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $segmentOccupancies;

    /** @var Collection<int, Booking> */
    #[ORM\OneToMany(mappedBy: 'trip', targetEntity: Booking::class)]
    private Collection $bookings;

    public function __construct()
    {
        $this->stops               = new ArrayCollection();
        $this->segmentOccupancies  = new ArrayCollection();
        $this->bookings            = new ArrayCollection();
        $this->status              = TripStatus::PUBLISHED;
    }

    public function getId(): ?int { return $this->id; }

    public function getDepartureAt(): ?\DateTimeInterface { return $this->departureAt; }
    public function setDepartureAt(\DateTimeInterface $departureAt): static { $this->departureAt = $departureAt; return $this; }

    public function getTotalSeats(): ?int { return $this->totalSeats; }
    public function setTotalSeats(int $totalSeats): static { $this->totalSeats = $totalSeats; return $this; }

    public function getPricePerKm(): ?string { return $this->pricePerKm; }
    public function setPricePerKm(string $pricePerKm): static { $this->pricePerKm = $pricePerKm; return $this; }

    public function getSuggestedPrice(): ?string { return $this->suggestedPrice; }
    public function setSuggestedPrice(?string $suggestedPrice): static { $this->suggestedPrice = $suggestedPrice; return $this; }

    public function getDistanceKm(): ?float { return $this->distanceKm; }
    public function setDistanceKm(?float $distanceKm): static { $this->distanceKm = $distanceKm; return $this; }

    public function getRouteGeometry(): ?string { return $this->routeGeometry; }
    public function setRouteGeometry(?string $routeGeometry): static { $this->routeGeometry = $routeGeometry; return $this; }

    public function getStatus(): ?TripStatus { return $this->status; }
    public function setStatus(TripStatus $status): static { $this->status = $status; return $this; }

    public function getVersion(): ?int { return $this->version; }

    public function getCompletedAt(): ?\DateTimeImmutable { return $this->completedAt; }
    public function setCompletedAt(?\DateTimeImmutable $completedAt): static { $this->completedAt = $completedAt; return $this; }

    public function getCancelledAt(): ?\DateTimeImmutable { return $this->cancelledAt; }
    public function setCancelledAt(?\DateTimeImmutable $cancelledAt): static { $this->cancelledAt = $cancelledAt; return $this; }

    public function getDriver(): ?User { return $this->driver; }
    public function setDriver(?User $driver): static { $this->driver = $driver; return $this; }

    public function getVehicle(): ?Vehicle { return $this->vehicle; }
    public function setVehicle(?Vehicle $vehicle): static { $this->vehicle = $vehicle; return $this; }

    /** @return Collection<int, TripStop> */
    public function getStops(): Collection { return $this->stops; }
    public function addStop(TripStop $stop): static
    {
        if (!$this->stops->contains($stop)) {
            $this->stops->add($stop);
            $stop->setTrip($this);
        }
        return $this;
    }
    public function removeStop(TripStop $stop): static
    {
        if ($this->stops->removeElement($stop)) {
            if ($stop->getTrip() === $this) {
                $stop->setTrip(null);
            }
        }
        return $this;
    }

    /** @return Collection<int, SegmentOccupancy> */
    public function getSegmentOccupancies(): Collection { return $this->segmentOccupancies; }
    public function addSegmentOccupancy(SegmentOccupancy $so): static
    {
        if (!$this->segmentOccupancies->contains($so)) {
            $this->segmentOccupancies->add($so);
            $so->setTrip($this);
        }
        return $this;
    }
    public function removeSegmentOccupancy(SegmentOccupancy $so): static
    {
        if ($this->segmentOccupancies->removeElement($so)) {
            if ($so->getTrip() === $this) {
                $so->setTrip(null);
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
            $booking->setTrip($this);
        }
        return $this;
    }
    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            if ($booking->getTrip() === $this) {
                $booking->setTrip(null);
            }
        }
        return $this;
    }
}