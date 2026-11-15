<?php

namespace App\Controller;

use App\Repository\CityRepository;
use App\Service\TripService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TripController extends AbstractController
{
    #[Route('/trips', name: 'app_trips_search', methods: ['GET'])]
    public function search(
        Request $request,
        TripService $tripService,
        CityRepository $cityRepository,
    ): Response {
        $from = $request->query->get('from');
        $to   = $request->query->get('to');
        $date = $request->query->get('date');

        $trips = $tripService->search($from, $to, $date);

        return $this->render('trip/search.html.twig', [
            'trips'  => $trips,
            'cities' => $cityRepository->findAllOrdered(),
            'from'   => $from,
            'to'     => $to,
            'date'   => $date,
        ]);
    }
}