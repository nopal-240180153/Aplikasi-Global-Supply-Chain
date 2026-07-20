<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiskScore;
use Illuminate\Http\Request;

class RiskApiController extends Controller
{
    /**
     * Display a listing of risk scores
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = RiskScore::with('country');

            // Filter by risk level
            if ($request->filled('risk_level')) {
                $query->where('risk_level', $request->risk_level);
            }

            // Filter by country
            if ($request->filled('country_id')) {
                $query->where('country_id', $request->country_id);
            }

            // Search by country name
            if ($request->filled('search')) {
                $search = $request->search;
                $query->whereHas('country', function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            }

            // Filter by score range
            if ($request->filled('min_score')) {
                $query->where('total_score', '>=', $request->min_score);
            }
            if ($request->filled('max_score')) {
                $query->where('total_score', '<=', $request->max_score);
            }

            // Sort
            $sortBy = $request->get('sort_by', 'total_score');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 10);
            $risks = $query->paginate($perPage);

            // Transform data
            $data = $risks->map(function($risk) {
                return [
                    'id' => $risk->id,
                    'country_id' => $risk->country_id,
                    'country_name' => $risk->country->name ?? 'Unknown',
                    'country_code' => $risk->country->code ?? null,
                    'total_score' => (float) $risk->total_score,
                    'risk_level' => $risk->risk_level,
                    'weather_score' => (float) $risk->weather_score,
                    'economy_score' => (float) $risk->economy_score,
                    'exchange_score' => (float) $risk->exchange_score,
                    'news_score' => (float) $risk->news_score,
                    'calculated_at' => $risk->calculated_at?->format('Y-m-d H:i:s'),
                    'created_at' => $risk->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Risk scores retrieved successfully',
                'data' => $data,
                'meta' => [
                    'total' => $risks->total(),
                    'per_page' => $risks->perPage(),
                    'current_page' => $risks->currentPage(),
                    'last_page' => $risks->lastPage(),
                    'from' => $risks->firstItem(),
                    'to' => $risks->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve risk scores',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified risk score
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $risk = RiskScore::with('country')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Risk score retrieved successfully',
                'data' => [
                    'id' => $risk->id,
                    'country_id' => $risk->country_id,
                    'country_name' => $risk->country->name ?? 'Unknown',
                    'country_code' => $risk->country->code ?? null,
                    'total_score' => (float) $risk->total_score,
                    'risk_level' => $risk->risk_level,
                    'weather_score' => (float) $risk->weather_score,
                    'economy_score' => (float) $risk->economy_score,
                    'exchange_score' => (float) $risk->exchange_score,
                    'news_score' => (float) $risk->news_score,
                    'calculated_at' => $risk->calculated_at?->format('Y-m-d H:i:s'),
                    'created_at' => $risk->created_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Risk score not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
