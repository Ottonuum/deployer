<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class WeatherController extends Controller
{
    private $apiKey;
    private $baseUrl;
    private const CACHE_DURATION = 1800; // 30 minutes in seconds

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key');
        $this->baseUrl = config('services.openweather.url', 'https://api.openweathermap.org/data/2.5/weather');
        
        if (!$this->apiKey) {
            throw new \RuntimeException('OpenWeather API key not found in configuration');
        }
    }

    public function getWeather($city)
    {
        // Check if weather data for this city is in cache
        $cacheKey = 'weather_' . strtolower($city);
        
        if (Cache::has($cacheKey)) {
            $weatherData = Cache::get($cacheKey);
            return view('weather', [
                'city' => $city,
                'weather' => $weatherData
            ]);
        }
        
        try {
            // Make API request to OpenWeather
            $response = Http::get($this->baseUrl, [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric' // For temperature in Celsius
            ]);
            
            if ($response->successful()) {
                $weatherData = $response->json();
                
                // Cache the weather data
                Cache::put($cacheKey, $weatherData, self::CACHE_DURATION);
                
                return view('weather', [
                    'city' => $city,
                    'weather' => $weatherData
                ]);
            } else {
                return view('weather', [
                    'city' => $city,
                    'error' => 'City not found or weather data unavailable'
                ]);
            }
        } catch (\Exception $e) {
            return view('weather', [
                'city' => $city,
                'error' => 'Failed to fetch weather data: ' . $e->getMessage()
            ]);
        }
    }
} 