<?php

namespace App\Entity;

use App\Enum\PaymentStatus;
use App\Repository\PaymentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PaymentRepository::class)]
class Payment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $amount = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $refundedAmount = null;

    #[ORM\Column(enumType: PaymentStatus::class)]
    private ?PaymentStatus $status = null;

    #[ORM\Column(length: 255)]
    private ?string $simulatedReference = null;

    #[ORM\OneToOne(inversedBy: 'payment', targetEntity: Booking::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Booking $booking = null;

    public function getId(): ?int { return $this->id; }

    public function getAmount(): ?string { return $this->amount; }
    public function setAmount(string $a): static { $this->amount = $a; return $this; }

    public function getRefundedAmount(): ?string { return $this->refundedAmount; }
    public function setRefundedAmount(?string $r): static { $this->refundedAmount = $r; return $this; }

    public function getStatus(): ?PaymentStatus { return $this->status; }
    public function setStatus(PaymentStatus $s): static { $this->status = $s; return $this; }

    public function getSimulatedReference(): ?string { return $this->simulatedReference; }
    public function setSimulatedReference(string $r): static { $this->simulatedReference = $r; return $this; }

    public function getBooking(): ?Booking { return $this->booking; }
    public function setBooking(?Booking $b): static { $this->booking = $b; return $this; }
}