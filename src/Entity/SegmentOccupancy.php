<?php

namespace App\Entity;

use App\Repository\SegmentOccupancyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SegmentOccupancyRepository::class)]
class SegmentOccupancy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $fromPosition = null;

    #[ORM\Column]
    private ?int $toPosition = null;

    #[ORM\Column]
    private ?int $bookedSeats = 0;

    #[ORM\Version]
    #[ORM\Column(type: 'integer')]
    private ?int $version = null;

    #[ORM\ManyToOne(inversedBy: 'segmentOccupancies')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    public function getId(): ?int { return $this->id; }

    public function getFromPosition(): ?int { return $this->fromPosition; }
    public function setFromPosition(int $p): static { $this->fromPosition = $p; return $this; }

    public function getToPosition(): ?int { return $this->toPosition; }
    public function setToPosition(int $p): static { $this->toPosition = $p; return $this; }

    public function getBookedSeats(): ?int { return $this->bookedSeats; }
    public function setBookedSeats(int $s): static { $this->bookedSeats = $s; return $this; }

    public function getVersion(): ?int { return $this->version; }

    public function getTrip(): ?Trip { return $this->trip; }
    public function setTrip(?Trip $trip): static { $this->trip = $trip; return $this; }
}