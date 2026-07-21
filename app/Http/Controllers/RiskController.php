<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;
use Illuminate\Http\Request;

class RiskController extends Controller
{
    /**
     * Halaman Analisis Risiko
     */
    public function index(Request $request)
    {
        $query = RiskScore::with('country');

        // Filter search by country name
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('country', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        // Filter by risk level (calculated from total_score)
        if ($request->filled('risk_level')) {
            $riskLevel = $request->input('risk_level');
            
            if ($riskLevel === 'Tinggi') {
                $query->where('total_score', '>=', 30);
            } elseif ($riskLevel === 'Sedang') {
                $query->where('total_score', '>=', 20)
                      ->where('total_score', '<', 30);
            } elseif ($riskLevel === 'Rendah') {
                $query->where('total_score', '<', 20);
            }
        }

        $risks = $query->orderByDesc('total_score')
            ->paginate(20)
            ->withQueryString();
        
        // Calculate risk level for each item
        foreach ($risks as $risk) {
            if ($risk->total_score >= 30) {
                $risk->risk_level = 'Tinggi';
            } elseif ($risk->total_score >= 20) {
                $risk->risk_level = 'Sedang';
            } else {
                $risk->risk_level = 'Rendah';
            }
        }

        $totalCountry = RiskScore::count();

        // Count by calculated risk level
        $highRisk = RiskScore::where('total_score', '>=', 30)->count();
        $mediumRisk = RiskScore::where('total_score', '>=', 20)
                               ->where('total_score', '<', 30)->count();
        $lowRisk = RiskScore::where('total_score', '<', 20)->count();

        return view('risk.index', compact(

            'risks',

            'totalCountry',

            'highRisk',

            'mediumRisk',

            'lowRisk'

        ));
    }
}