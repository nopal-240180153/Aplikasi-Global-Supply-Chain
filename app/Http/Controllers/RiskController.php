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

        // Filter by risk level
        if ($request->filled('risk_level')) {
            $query->where('risk_level', $request->input('risk_level'));
        }

        $risks = $query->orderByDesc('total_score')
            ->paginate(20)
            ->withQueryString();

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