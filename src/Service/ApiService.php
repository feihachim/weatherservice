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

        //return $content;
        return ['code' => $statusCode, 'content-type' => $contentType, 'data' => $content];
    }

    public function fetchFccWeatherInfo(array $data)
    {
        $response = $this->client->request(
            'GET',
            'https://weather-proxy.freecodecamp.rocks/api/current',
            [
                'query' => [
                    'lat' => $data['data'][0]['lat'],
                    'lon' => $data['data'][0]['lon']
                ]
            ]
        );

        $statusCode = $response->getStatusCode();
        $contentType = $response->getHeaders()['content-type'][0];

        $content = $response->toArray();
        if ($content !== [])
        {
            $result = ['weather' => $content['weather'][0], 'main' => $content['main'], 'sys' => $content['sys']['country'], 'name' => $content['name']];
        }
        else
        {
            $result = $content;
        }
        //$result = $content;
        return ['code' => $statusCode, 'data' => $result];
    }

    public function WeatherTownService(string $city, string $apiKey)
    {
        $geocodeInfo = $this->fetchGeocodeInfo($city, $apiKey);
        if ($geocodeInfo['data'] !== [])
        {
            $weatherInfo = $this->fetchFccWeatherInfo($geocodeInfo);
            return $weatherInfo;
        }
        else
        {
            return $geocodeInfo;
        }
    }
}
