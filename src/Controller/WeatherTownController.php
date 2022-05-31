<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ApiService;
use Symfony\Component\HttpFoundation\Response;

class WeatherTownController extends AbstractController
{
    /**
     * @Route("/weather/town", name="app_weather_town")
     */
    public function index(Request $request, ApiService $apiService): JsonResponse
    {
        $response = $apiService->WeatherTownService('Marseille', $this->getParameter('app.api_key'));
        return $this->json([
            'response' => $response
        ]);
    }

    /**
     * Undocumented function
     *
     * @Route("/weather/test",name="app_weather_test")
     */
    public function test(ApiService $apiService): Response
    {
        $geoTown = $apiService->fetchGeocodeInfo('London', $this->getParameter('app.api_key'));
        return $this->json([
            'message' => $geoTown
        ]);
    }
}
