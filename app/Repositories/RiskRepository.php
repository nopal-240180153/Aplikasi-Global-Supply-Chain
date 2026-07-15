<?php

namespace App\Repositories;

use App\Models\RiskScore;

class RiskRepository
{
    /**
     * Ambil seluruh data risiko.
     */
    public function all()
    {
        return RiskScore::with('country')
            ->orderByDesc('total_score')
            ->get();
    }

    /**
     * Ambil berdasarkan negara.
     */
    public function findByCountry(int $countryId): ?RiskScore
    {
        return RiskScore::where('country_id', $countryId)
            ->first();
    }

    /**
     * Simpan / update hasil analisis.
     */
    public function updateOrCreate(array $attributes, array $values)
    {
        return RiskScore::updateOrCreate(
            $attributes,
            $values
        );
    }

    /**
     * Negara dengan risiko tinggi.
     */
    public function highRisk()
    {
        return RiskScore::with('country')
            ->where('risk_level', 'Tinggi')
            ->orderByDesc('total_score')
            ->get();
    }

    /**
     * Negara dengan risiko sedang.
     */
    public function mediumRisk()
    {
        return RiskScore::with('country')
            ->where('risk_level', 'Sedang')
            ->orderByDesc('total_score')
            ->get();
    }

    /**
     * Negara dengan risiko rendah.
     */
    public function lowRisk()
    {
        return RiskScore::with('country')
            ->where('risk_level', 'Rendah')
            ->orderByDesc('total_score')
            ->get();
    }
}