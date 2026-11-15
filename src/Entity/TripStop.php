<?php

namespace App\Entity;

use App\Repository\TripStopRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripStopRepository::class)]
class TripStop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $position = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $arrivalAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTime $departureAt = null;

    #[ORM\Column]
    private ?float $distanceFromStartKm = null;

    #[ORM\ManyToOne(inversedBy: 'stops')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Trip $trip = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?City $city = null;

    public function getId(): ?int { return $this->id; }

    public function getPosition(): ?int { return $this->position; }
    public function setPosition(int $position): static { $this->position = $position; return $this; }

    public function getArrivalAt(): ?\DateTime { return $this->arrivalAt; }
    public function setArrivalAt(?\DateTime $arrivalAt): static { $this->arrivalAt = $arrivalAt; return $this; }

    public function getDepartureAt(): ?\DateTime { return $this->departureAt; }
    public function setDepartureAt(?\DateTime $departureAt): static { $this->departureAt = $departureAt; return $this; }

    public function getDistanceFromStartKm(): ?float { return $this->distanceFromStartKm; }
    public function setDistanceFromStartKm(float $km): static { $this->distanceFromStartKm = $km; return $this; }

    public function getTrip(): ?Trip { return $this->trip; }
    public function setTrip(?Trip $trip): static { $this->trip = $trip; return $this; }

    public function getCity(): ?City { return $this->city; }
    public function setCity(?City $city): static { $this->city = $city; return $this; }
}