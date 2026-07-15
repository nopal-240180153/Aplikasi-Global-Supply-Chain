<?php

namespace App\Http\Controllers;

use App\Models\RiskScore;

class RiskController extends Controller
{
    /**
     * Halaman Analisis Risiko
     */
    public function index()
    {
        $risks = RiskScore::with('country')
            ->orderByDesc('total_score')
            ->paginate(20);

        $totalCountry = RiskScore::count();

        $highRisk = RiskScore::where('risk_level', 'Tinggi')->count();

        $mediumRisk = RiskScore::where('risk_level', 'Sedang')->count();

        $lowRisk = RiskScore::where('risk_level', 'Rendah')->count();

        return view('risk.index', compact(

            'risks',

            'totalCountry',

            'highRisk',

            'mediumRisk',

            'lowRisk'

        ));
    }
}