<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Port;
use Illuminate\Http\Request;

class PortApiController extends Controller
{
    /**
     * Display a listing of ports
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Port::with('country');

            // Search by port name
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where('name', 'like', "%{$search}%");
            }

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

            // Filter by region
            if ($request->filled('region')) {
                $query->where('region', 'like', "%{$request->region}%");
            }

            // Filter by coordinates (bounding box)
            if ($request->filled('min_lat') && $request->filled('max_lat') && 
                $request->filled('min_lng') && $request->filled('max_lng')) {
                $query->whereBetween('latitude', [$request->min_lat, $request->max_lat])
                      ->whereBetween('longitude', [$request->min_lng, $request->max_lng]);
            }

            // Sort
            $sortBy = $request->get('sort_by', 'name');
            $sortOrder = $request->get('sort_order', 'asc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $ports = $query->paginate($perPage);

            // Transform data
            $data = $ports->map(function($port) {
                return [
                    'id' => $port->id,
                    'name' => $port->name,
                    'country_id' => $port->country_id,
                    'country_name' => $port->country->name ?? 'Unknown',
                    'country_code' => $port->country->code ?? null,
                    'region' => $port->region,
                    'coordinates' => [
                        'latitude' => (float) $port->latitude,
                        'longitude' => (float) $port->longitude,
                    ],
                    'port_code' => $port->port_code,
                    'facilities' => $port->facilities,
                    'created_at' => $port->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'Ports retrieved successfully',
                'data' => $data,
                'meta' => [
                    'total' => $ports->total(),
                    'per_page' => $ports->perPage(),
                    'current_page' => $ports->currentPage(),
                    'last_page' => $ports->lastPage(),
                    'from' => $ports->firstItem(),
                    'to' => $ports->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve ports',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified port
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $port = Port::with('country')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Port retrieved successfully',
                'data' => [
                    'id' => $port->id,
                    'name' => $port->name,
                    'country_id' => $port->country_id,
                    'country_name' => $port->country->name ?? 'Unknown',
                    'country_code' => $port->country->code ?? null,
                    'region' => $port->region,
                    'coordinates' => [
                        'latitude' => (float) $port->latitude,
                        'longitude' => (float) $port->longitude,
                    ],
                    'port_code' => $port->port_code,
                    'facilities' => $port->facilities,
                    'created_at' => $port->created_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Port not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
