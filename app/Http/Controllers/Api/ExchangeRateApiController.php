<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ExchangeRate;
use Illuminate\Http\Request;

class ExchangeRateApiController extends Controller
{
    /**
     * Display a listing of exchange rates (currency data)
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = ExchangeRate::with('country');

            // Filter by country
            if ($request->filled('country_id')) {
                $query->where('country_id', $request->country_id);
            }

            // Filter by country name
            if ($request->filled('country')) {
                $country = $request->country;
                $query->whereHas('country', function($q) use ($country) {
                    $q->where('name', 'like', "%{$country}%");
                });
            }

            // Filter by base currency
            if ($request->filled('base_currency')) {
                $query->where('base_currency', strtoupper($request->base_currency));
            }

            // Filter by target currency
            if ($request->filled('target_currency')) {
                $query->where('target_currency', strtoupper($request->target_currency));
            }

            // Filter by rate range
            if ($request->filled('min_rate')) {
                $query->where('exchange_rate', '>=', $request->min_rate);
            }
            if ($request->filled('max_rate')) {
                $query->where('exchange_rate', '<=', $request->max_rate);
            }

            // Filter by date
            if ($request->filled('date')) {
                $query->whereDate('recorded_at', $request->date);
            }

            // Filter by date range
            if ($request->filled('from_date')) {
                $query->whereDate('recorded_at', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('recorded_at', '<=', $request->to_date);
            }

            // Sort
            $sortBy = $request->get('sort_by', 'recorded_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 10);
            $rates = $query->paginate($perPage);

            // Transform data
            $data = $rates->map(function($rate) {
                return [
                    'id' => $rate->id,
                    'country_id' => $rate->country_id,
                    'country_name' => $rate->country->name ?? 'Unknown',
                    'country_code' => $rate->country->code ?? null,
                    'base_currency' => $rate->base_currency,
                    'target_currency' => $rate->target_currency,
                    'exchange_rate' => (float) $rate->exchange_rate,
                    'rate_formatted' => '1 ' . $rate->base_currency . ' = ' . number_format($rate->exchange_rate, 4) . ' ' . $rate->target_currency,
                    'recorded_at' => $rate->recorded_at?->format('Y-m-d H:i:s'),
                    'created_at' => $rate->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Exchange rates retrieved successfully',
                'data' => $data,
                'meta' => [
                    'total' => $rates->total(),
                    'per_page' => $rates->perPage(),
                    'current_page' => $rates->currentPage(),
                    'last_page' => $rates->lastPage(),
                    'from' => $rates->firstItem(),
                    'to' => $rates->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve exchange rates',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified exchange rate
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $rate = ExchangeRate::with('country')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Exchange rate retrieved successfully',
                'data' => [
                    'id' => $rate->id,
                    'country_id' => $rate->country_id,
                    'country_name' => $rate->country->name ?? 'Unknown',
                    'country_code' => $rate->country->code ?? null,
                    'base_currency' => $rate->base_currency,
                    'target_currency' => $rate->target_currency,
                    'exchange_rate' => (float) $rate->exchange_rate,
                    'rate_formatted' => '1 ' . $rate->base_currency . ' = ' . number_format($rate->exchange_rate, 4) . ' ' . $rate->target_currency,
                    'recorded_at' => $rate->recorded_at?->format('Y-m-d H:i:s'),
                    'created_at' => $rate->created_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Exchange rate not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Get latest exchange rates for all countries
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function latest()
    {
        try {
            $rates = ExchangeRate::with('country')
                ->select('country_id', 'base_currency', 'target_currency', 'exchange_rate', 'recorded_at')
                ->whereIn('id', function($query) {
                    $query->selectRaw('MAX(id)')
                        ->from('exchange_rates')
                        ->groupBy('country_id');
                })
                ->orderBy('exchange_rate', 'desc')
                ->get();

            $data = $rates->map(function($rate) {
                return [
                    'country_name' => $rate->country->name ?? 'Unknown',
                    'country_code' => $rate->country->code ?? null,
                    'base_currency' => $rate->base_currency,
                    'target_currency' => $rate->target_currency,
                    'exchange_rate' => (float) $rate->exchange_rate,
                    'recorded_at' => $rate->recorded_at?->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Latest exchange rates retrieved successfully',
                'data' => $data
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve latest exchange rates',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
