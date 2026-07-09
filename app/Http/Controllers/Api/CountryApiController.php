<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryApiController extends Controller
{
    /**
     * GET /api/countries
     */
    public function index(Request $request)
    {
        $query = Country::query();

        // Search nama negara
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter region
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        // Pagination
        $countries = $query
            ->orderBy('name')
            ->paginate(
                $request->get('per_page', 20)
            );

        return response()->json([
            'success' => true,
            'message' => 'Data negara berhasil diambil.',
            'data' => $countries
        ]);
    }

    /**
     * GET /api/countries/{id}
     */
    public function show($id)
    {
        $country = Country::findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $country
        ]);
    }
}