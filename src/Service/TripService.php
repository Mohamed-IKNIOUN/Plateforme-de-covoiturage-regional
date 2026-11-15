<?php

namespace App\Service;

use App\Entity\Trip;
use App\Repository\CityRepository;
use App\Repository\TripRepository;

class TripService
{
    public function __construct(
        private TripRepository $tripRepository,
        private CityRepository $cityRepository,
    ) {
    }

    /**
     * @return Trip[]
     */
    public function search(?string $fromName, ?string $toName, ?string $dateStr): array
    {
        $from = $fromName ? $this->cityRepository->findOneByName($fromName) : null;
        $to   = $toName   ? $this->cityRepository->findOneByName($toName)   : null;

        // If a city name was given but doesn't exist, return nothing
        if (($fromName && !$from) || ($toName && !$to)) {
            return [];
        }

        $date = null;
        if ($dateStr) {
            $parsed = \DateTime::createFromFormat('Y-m-d', $dateStr);
            if ($parsed !== false) {
                $date = $parsed;
            }
        }

        return $this->tripRepository->search($from, $to, $date);
    }
}