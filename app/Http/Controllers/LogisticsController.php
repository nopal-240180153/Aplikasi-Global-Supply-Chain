<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\Port;
use App\Services\API\WeatherApiService;

class LogisticsController extends Controller
{
    public function index()
    {
        // Get all countries that have at least one port
        $countries = Country::whereHas('ports')->orderBy('name')->get();
        return view('logistics.index', compact('countries'));
    }

    public function getPortsByCountry($country_id)
    {
        $ports = Port::where('country_id', $country_id)->orderBy('port_name')->get();
        return response()->json($ports);
    }

    public function calculate(Request $request, WeatherApiService $weatherService)
    {
        $request->validate([
            'origin_port_id' => 'required|exists:ports,id',
            'destination_port_id' => 'required|exists:ports,id',
        ]);

        $originPort = Port::with('country')->findOrFail($request->origin_port_id);
        $destPort = Port::with('country')->findOrFail($request->destination_port_id);

        if ($originPort->id === $destPort->id) {
            return response()->json([
                'error' => 'Pelabuhan asal dan tujuan tidak boleh sama.'
            ], 400);
        }

        // Calculate Distance (dengan Detour Factor 1.25 untuk rute laut/manuver daratan)
        $straightDistanceKm = $this->calculateDistance($originPort->latitude, $originPort->longitude, $destPort->latitude, $destPort->longitude);
        $distanceKm = $straightDistanceKm * 1.25;
        $distanceNm = $distanceKm * 0.539957;

        // Calculate Time (20 knots average cargo ship speed)
        $speedKnots = 20; 
        $speedKmh = $speedKnots * 1.852;
        $timeHours = $distanceKm / $speedKmh;
        $days = floor($timeHours / 24);
        $hours = round($timeHours % 24);

        // Fetch Weather
        try {
            $originWeather = $weatherService->current($originPort->latitude, $originPort->longitude);
            $destWeather = $weatherService->current($destPort->latitude, $destPort->longitude);
        } catch (\Exception $e) {
            $originWeather = null;
            $destWeather = null;
        }

        // Format Weather Data
        $weatherData = [
            'origin' => $this->formatWeather($originWeather, $originPort->port_name),
            'destination' => $this->formatWeather($destWeather, $destPort->port_name)
        ];

        // Risk & Exchange (Destination Country)
        $destCountry = $destPort->country;
        $baseRiskScore = $destCountry->risk_score ?? 0;
        $exchangeRate = $destCountry->exchange_rate ?? 1;

        // Hitung dynamic risk score berdasarkan cuaca saat ini (penalti jika cuaca buruk)
        $dynamicRiskScore = $baseRiskScore;
        $isWeatherBad = false;

        if (isset($destWeather['current'])) {
            $wind = $destWeather['current']['wind_speed_10m'] ?? 0;
            if ($wind > 80) {
                $dynamicRiskScore += 25;
                $isWeatherBad = true;
            } elseif ($wind > 40) {
                $dynamicRiskScore += 15;
                $isWeatherBad = true;
            }
        }
        $dynamicRiskScore = min(100, $dynamicRiskScore);

        if ($dynamicRiskScore < 20) {
            $dynamicRiskLevel = 'Rendah';
        } elseif ($dynamicRiskScore < 35) {
            $dynamicRiskLevel = 'Sedang';
        } else {
            $dynamicRiskLevel = 'Tinggi';
        }

        return response()->json([
            'distance_km' => round($distanceKm, 2),
            'distance_nm' => round($distanceNm, 2),
            'estimated_days' => $days,
            'estimated_hours' => $hours,
            'weather' => $weatherData,
            'destination_risk' => [
                'score' => $dynamicRiskScore,
                'level' => $dynamicRiskLevel . ($isWeatherBad ? ' (Cuaca Buruk)' : '')
            ],
            'destination_exchange' => [
                'currency' => $destCountry->currency_code ?? 'USD',
                'rate' => $exchangeRate
            ],
            'origin_port' => $originPort,
            'destination_port' => $destPort,
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km
        
        $lat1 = deg2rad($lat1);
        $lon1 = deg2rad($lon1);
        $lat2 = deg2rad($lat2);
        $lon2 = deg2rad($lon2);

        $dlat = $lat2 - $lat1;
        $dlon = $lon2 - $lon1;

        $a = sin($dlat/2) * sin($dlat/2) + cos($lat1) * cos($lat2) * sin($dlon/2) * sin($dlon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }

    private function formatWeather($weatherResponse, $portName)
    {
        if (!$weatherResponse || !isset($weatherResponse['current'])) {
            return [
                'port_name' => $portName,
                'status' => 'Data tidak tersedia',
                'warning' => false
            ];
        }

        $current = $weatherResponse['current'];
        $windSpeed = $current['wind_speed_10m'] ?? 0;
        $temp = $current['temperature_2m'] ?? 0;
        
        // Basic warning logic
        $warning = false;
        $warningMsg = '';
        if ($windSpeed > 40) {
            $warning = true;
            $warningMsg = 'Angin kencang terpantau, potensi delay kapal.';
        }

        return [
            'port_name' => $portName,
            'temperature' => $temp,
            'wind_speed' => $windSpeed,
            'warning' => $warning,
            'warning_msg' => $warningMsg,
            'status' => 'Berhasil mengambil data'
        ];
    }
}
