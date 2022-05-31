<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiService
{
    private $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetchGeocodeInfo(string $city, string $apiKey)
    {
        $response = $this->client->request(
            'GET',
            'http://api.openweathermap.org/geo/1.0/direct',
            [
                'query' => [
                    'q' => $city,
                    'appid' => $apiKey
                ]
            ]
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        $result = ['lat' => $content[0]['lat'], 'lon' => $content[0]['lon'], 'state' => $content[0]['state']];
        //return $content;
        return $result;
    }

    public function fetchFccWeatherInfo(array $data)
    {
        $response = $this->client->request(
            'GET',
            'https://weather-proxy.freecodecamp.rocks/api/current',
            [
                'query' => [
                    'lat' => $data['lat'],
                    'lon' => $data['lon']
                ]
            ]
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];
        $content = $response->getContent();
        $content = $response->toArray();
        $result = ['state' => $data['state'], 'message' => $content];
        return $result;
    }

    public function WeatherTownService(string $city, string $apiKey)
    {
        $geocodeInfo = $this->fetchGeocodeInfo($city, $apiKey);
        $weatherInfo = $this->fetchFccWeatherInfo($geocodeInfo);
        $result = ['message' => $weatherInfo];
        return $result;
    }
}
