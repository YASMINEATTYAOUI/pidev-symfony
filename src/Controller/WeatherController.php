<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherController extends AbstractController
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    #[Route('/weather', name: 'app_weather')]
    public function index(Request $request): Response
    {
        $cityName = $request->query->get('city', '');
        $date = $request->query->get('date', date('Y-m-d'));
        $weatherData = null;
        $error = null;

        if ($cityName) {
            try {
                $weatherData = $this->getWeatherForCityAndDate($cityName, $date);
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }
        }

        return $this->render('weather/weather.html.twig', [
            'cityName' => $cityName,
            'date' => $date,
            'weatherData' => $weatherData,
            'error' => $error
        ]);
    }

    private function getWeatherForCityAndDate(string $cityName, string $date): array
    {
        $coordinates = $this->getCoordinatesForCity($cityName);
        
        if (!$coordinates) {
            throw new \Exception("Could not find coordinates for city: $cityName");
        }

        $formattedDate = date('Y-m-d', strtotime($date));

        $url = "https://api.open-meteo.com/v1/forecast" .
               "?latitude=" . $coordinates['latitude'] .
               "&longitude=" . $coordinates['longitude'] .
               "&start_date=" . $formattedDate .
               "&end_date=" . $formattedDate .
               "&daily=temperature_2m_max,temperature_2m_min,precipitation_sum,weathercode,uv_index_max" .
               "&timezone=auto";

        $response = $this->httpClient->request('GET', $url);
        
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Error fetching weather data: " . $response->getStatusCode());
        }

        $data = $response->toArray();
        
        return $this->parseWeatherData($data, $cityName, $formattedDate);
    }

    private function getCoordinatesForCity(string $cityName): ?array
    {
        $geocodeUrl = "https://geocoding-api.open-meteo.com/v1/search?name=" . 
                      urlencode($cityName) . "&count=1";

        $response = $this->httpClient->request('GET', $geocodeUrl);
        
        if ($response->getStatusCode() !== 200) {
            throw new \Exception("Error geocoding city: " . $response->getStatusCode());
        }

        $data = $response->toArray();
        
        if (!isset($data['results']) || count($data['results']) === 0) {
            return null;
        }

        $firstResult = $data['results'][0];
        
        return [
            'latitude' => $firstResult['latitude'],
            'longitude' => $firstResult['longitude'],
        ];
    }

    private function parseWeatherData(array $json, string $cityName, string $date): array
    {
        $daily = $json['daily'];

        $maxTemps = $daily['temperature_2m_max'];
        $minTemps = $daily['temperature_2m_min'];
        $precipitations = $daily['precipitation_sum'];
        $weatherCodes = $daily['weathercode'];
        $uvIndices = $daily['uv_index_max'] ?? [0];

        $maxTemp = $maxTemps[0];
        $minTemp = $minTemps[0];
        $precipitation = $precipitations[0];
        $weatherCode = $weatherCodes[0];
        $uvIndex = $uvIndices[0];
        
        return [
            'cityName' => $cityName,
            'maxTemperature' => round($maxTemp),
            'minTemperature' => round($minTemp),
            'precipitation' => $precipitation,
            'weatherDescription' => $this->getWeatherDescription($weatherCode),
            'weatherIcon' => $this->getWeatherIcon($weatherCode),
            'weatherIconPath' => $this->getWeatherIconPath($this->getWeatherDescription($weatherCode)),
            'formattedDate' => date('l, F j, Y', strtotime($date)),
            'uvIndex' => round($uvIndex),
            'uvDescription' => $this->getUVDescription($uvIndex),
            'currentTime' => date('H:i')
        ];
    }

    private function getWeatherDescription(int $weatherCode): string
    {
        switch ($weatherCode) {
            case 0: return "Clear sky";
            case 1: return "Mainly clear";
            case 2: return "Partly cloudy";
            case 3: return "Cloudy";
            case 45: case 48: return "Fog";
            case 51: case 53: case 55: return "Drizzle";
            case 56: case 57: return "Freezing Drizzle";
            case 61: case 63: case 65: return "Rain";
            case 66: case 67: return "Freezing Rain";
            case 71: case 73: case 75: return "Snow fall";
            case 77: return "Snow grains";
            case 80: case 81: case 82: return "Rain showers";
            case 85: case 86: return "Snow showers";
            case 95: return "Thunderstorm";
            case 96: case 99: return "Thunderstorm with hail";
            default: return "Unknown";
        }
    }

    private function getWeatherIcon(int $weatherCode): string
    {
        switch ($weatherCode) {
            case 0: return "sun";
            case 1: return "sun-cloud";
            case 2: return "cloud-sun";
            case 3: return "cloud";
            case 45: case 48: return "fog";
            case 51: case 53: case 55: return "cloud-drizzle";
            case 56: case 57: return "cloud-drizzle";
            case 61: case 63: case 65: return "cloud-rain";
            case 66: case 67: return "cloud-snow";
            case 71: case 73: case 75: case 77: return "cloud-snow";
            case 80: case 81: case 82: return "cloud-showers-heavy";
            case 85: case 86: return "cloud-snow";
            case 95: case 96: case 99: return "cloud-bolt";
            default: return "question";
        }
    }

    /**
 * Get weather icon path based on weather description
 */
private function getWeatherIconPath(string $weatherDescription): string
{
    $lowerDesc = strtolower($weatherDescription);

    if (strpos($lowerDesc, 'clear') !== false || strpos($lowerDesc, 'mainly clear') !== false) {
        return '/images/weather/sunny.png';
    } elseif (strpos($lowerDesc, 'partly cloudy') !== false) {
        return '/images/weather/partly_cloudy.png';
    } elseif (strpos($lowerDesc, 'overcast') !== false || strpos($lowerDesc, 'cloudy') !== false) {
        return '/images/weather/cloudy.png';
    } elseif (strpos($lowerDesc, 'fog') !== false) {
        return '/images/weather/fog.png';
    } elseif (strpos($lowerDesc, 'drizzle') !== false) {
        return '/images/weather/drizzle.png';
    } elseif (strpos($lowerDesc, 'rain') !== false) {
        return '/images/weather/rain.png';
    } elseif (strpos($lowerDesc, 'snow') !== false) {
        return '/images/weather/snow.png';
    } elseif (strpos($lowerDesc, 'thunder') !== false) {
        return '/images/weather/thunderstorm.png';
    } else {
        return '/images/weather/unknown.png';
    }
}

    private function getUVDescription(float $uvIndex): string
    {
        if ($uvIndex < 3) {
            return "Low";
        } elseif ($uvIndex < 6) {
            return "Moderate";
        } elseif ($uvIndex < 8) {
            return "High";
        } elseif ($uvIndex < 11) {
            return "Very High";
        } else {
            return "Extreme";
        }
    }
}