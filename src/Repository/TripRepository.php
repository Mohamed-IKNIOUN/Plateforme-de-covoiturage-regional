<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Trip;
use App\Enum\TripStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Trip>
 */
class TripRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Trip::class);
    }

    /**
     * Find published trips that go from $from city to $to city on a given day.
     *
     * A trip matches if it contains BOTH cities as stops AND the departure
     * stop comes strictly before the arrival stop (you can't travel backwards).
     *
     * @return Trip[]
     */
    public function search(?City $from, ?City $to, ?\DateTimeInterface $date): array
    {
        $qb = $this->createQueryBuilder('t')
            ->leftJoin('t.stops', 'depStop')
            ->leftJoin('t.stops', 'arrStop')
            ->addSelect('depStop', 'arrStop')
            ->andWhere('t.status = :status')
            ->setParameter('status', TripStatus::PUBLISHED)
            ->andWhere('depStop.position < arrStop.position')
            ->orderBy('t.departureAt', 'ASC');

        if ($from !== null) {
            $qb->andWhere('depStop.city = :from')
               ->setParameter('from', $from);
        }

        if ($to !== null) {
            $qb->andWhere('arrStop.city = :to')
               ->setParameter('to', $to);
        }

        if ($date !== null) {
            $start = (clone $date)->setTime(0, 0, 0);
            $end   = (clone $date)->setTime(23, 59, 59);

            $qb->andWhere('t.departureAt BETWEEN :start AND :end')
               ->setParameter('start', $start)
               ->setParameter('end', $end);
        }

        return $qb->getQuery()->getResult();
    }
}