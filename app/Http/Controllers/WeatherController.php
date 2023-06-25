<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\OpenWeatherMapService;
use Curl\Curl;

class WeatherController extends Controller
{
    protected $openWeatherMap;

    public $lon;
    public $lat;

    public function getTemperature(Request $request)
    {
        $apiKey = env('OPEN_WEATHER_MAP_API_KEY');
        $city = $request->input('city', 'auto');
        $cnt = 6;

        if ($city === 'auto') {
            $city = 'London';
        }


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.openweathermap.org/data/2.5/forecast?appid={$apiKey}&q={$city}&cnt=6");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CAINFO, base_path('cacert.pem'));

        $apikay = "https://api.openweathermap.org/data/2.5/forecast?appid={$apiKey}&q={$city}&cnt=7";

        $response = curl_exec($ch);

        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return "Nu s-a putut obține temperatura pentru {$city}. Eroare cURL: " . $error;
        }

        curl_close($ch);

        $weatherData = json_decode($response, true);
        $forecastData = $weatherData['list'];
    
        $weatherByDay = [];

        $teperature = $forecastData[0]['main']['temp'];

        $kelvinToCelsius = $teperature - 273.15;
        $formattedTemperature = number_format($kelvinToCelsius, 1);


            $temperatures = [];
            $celsiusTemperatures = [];
            $descriptions = [];
            $icons = [];

            foreach ($weatherData['list'] as $w) {
                $temperatures[] = $w['main']['temp'];
                $descriptions[] = $w['weather'][0]['description'];
                $icons[] = $w['weather'][0]['icon'];
            }

            foreach ($temperatures as $temp) {
                $celsiusTemp = $temp - 273.15;
                $formattedTemperature = number_format($celsiusTemp, 1);
                $celsiusTemperatures[] = $formattedTemperature;
            }

        return view('welcome', [
            'city' => $city,
            'temperature' => $formattedTemperature,
            'description' => $descriptions,
            'weatherData' => $weatherData,
            'today' => $forecastData[0],
            'apikey' => $apikay,
            'todayDet' => $weatherData,
        ]);
    }



    public function __construct(OpenWeatherMapService $openWeatherMap)
    {
        $this->openWeatherMap = $openWeatherMap;
    }

    public function getCurrentWeather($city)
    {
        $weatherData = $this->openWeatherMap->getCurrentWeather($city);
        return response()->json($weatherData);
    }
}
