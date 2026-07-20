<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\RiskScore;
use App\Models\WeatherLog;
use App\Models\NewsArticle;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    /**
     * Get real-time dashboard stats
     */
    public function stats()
    {
        $stats = [
            'countries' => Country::count(),
            'high_risk' => RiskScore::where('risk_level', 'High')->count(),
            'medium_risk' => RiskScore::where('risk_level', 'Medium')->count(),
            'low_risk' => RiskScore::where('risk_level', 'Low')->count(),
            'latest_weather' => WeatherLog::latest('recorded_at')->first(),
            'latest_news' => NewsArticle::latest('published_at')->first(),
            'latest_update' => now()->format('H:i:s'),
        ];

        return response()->json([
            'success' => true,
            'data' => $stats,
            'timestamp' => now()->toIso8601String(),
        ]);
    }

    /**
     * Get top risk countries (real-time)
     */
    public function topRisk()
    {
        $topRisk = RiskScore::with('country')
            ->orderByDesc('total_score')
            ->take(5)
            ->get()
            ->map(function($risk) {
                return [
                    'country' => $risk->country->name ?? 'Unknown',
                    'flag' => $risk->country->flag ?? '',
                    'score' => round($risk->total_score, 2),
                    'level' => $risk->risk_level,
                    'updated_at' => $risk->calculated_at->diffForHumans(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $topRisk,
        ]);
    }

    /**
     * Get weather alerts (real-time)
     */
    public function weatherAlerts()
    {
        $alerts = WeatherLog::with('country')
            ->where('temperature', '>', 35)
            ->orWhere('wind_speed', '>', 50)
            ->latest('recorded_at')
            ->take(5)
            ->get()
            ->map(function($weather) {
                return [
                    'country' => $weather->country->name ?? 'Unknown',
                    'condition' => $weather->condition,
                    'temperature' => $weather->temperature,
                    'wind_speed' => $weather->wind_speed,
                    'severity' => $weather->temperature > 40 || $weather->wind_speed > 70 ? 'Danger' : 'Warning',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $alerts,
        ]);
    }
}
