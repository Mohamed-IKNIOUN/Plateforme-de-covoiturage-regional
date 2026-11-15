<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Trip;
use App\Entity\TripStop;
use App\Entity\User;
use App\Entity\Vehicle;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // ── Cities ──────────────────────────────────────────────
        $cities = [];
        foreach ([
            ['Casablanca',  'Casablanca-Settat',  33.5731, -7.5898],
            ['Rabat',       'Rabat-Salé-Kénitra', 34.0209, -6.8416],
            ['Marrakech',   'Marrakech-Safi',     31.6295, -7.9811],
            ['Fès',         'Fès-Meknès',         34.0331, -5.0003],
            ['Tanger',      'Tanger-Tétouan',     35.7595, -5.8340],
            ['Agadir',      'Souss-Massa',        30.4278, -9.5981],
        ] as [$name, $region, $lat, $lng]) {
            $city = (new City())
                ->setName($name)
                ->setRegion($region)
                ->setLatitude($lat)
                ->setLongitude($lng);
            $manager->persist($city);
            $cities[$name] = $city;
        }

        // ── Users ───────────────────────────────────────────────
        $driver = (new User())
            ->setEmail('driver@test.com')
            ->setFirstName('Youssef')
            ->setLastName('Alaoui')
            ->setPhone('0612345678')
            ->setCreatedAt(new \DateTime());
        $driver->setPassword($this->hasher->hashPassword($driver, 'password'));
        $manager->persist($driver);

        $passenger = (new User())
            ->setEmail('passenger@test.com')
            ->setFirstName('Sara')
            ->setLastName('Benali')
            ->setPhone('0698765432')
            ->setCreatedAt(new \DateTime());
        $passenger->setPassword($this->hasher->hashPassword($passenger, 'password'));
        $manager->persist($passenger);

        // ── Vehicle ─────────────────────────────────────────────
        $vehicle = (new Vehicle())
            ->setOwner($driver)
            ->setBrand('Dacia')
            ->setModel('Logan')
            ->setColor('White')
            ->setPlateNumber('12345-A-6')
            ->setSeats(4);
        $manager->persist($vehicle);

        // ── Trip: Casablanca → Rabat → Marrakech ────────────────
        $trip = (new Trip())
            ->setDriver($driver)
            ->setVehicle($vehicle)
            ->setDepartureAt(new \DateTime('+2 days 08:00'))
            ->setTotalSeats(3)
            ->setPricePerKm('1.20')
            ->setDistanceKm(327.0)
            ->setSuggestedPrice('392.40');
        $manager->persist($trip);

        $stops = [
            ['Casablanca', 0, 0.0,   '08:00'],
            ['Rabat',      1, 87.0,  '09:15'],
            ['Marrakech',  2, 327.0, '13:00'],
        ];
        foreach ($stops as [$cityName, $position, $km, $time]) {
            $stop = (new TripStop())
                ->setTrip($trip)
                ->setCity($cities[$cityName])
                ->setPosition($position)
                ->setDistanceFromStartKm($km)
                ->setArrivalAt(new \DateTime('+2 days ' . $time));
            $manager->persist($stop);
        }

        $manager->flush();
    }
}