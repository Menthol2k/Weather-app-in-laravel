<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class OpenWeatherMapService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = config('services.openweathermap.api_key');
    }

    public function getCurrentWeather($city)
    {
        $url = "https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$this->apiKey}";
        $response = Http::get($url);

        return $response->json();
    }
}
