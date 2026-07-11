<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $query = Country::query();

        // Pencarian nama negara
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter region
        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }

        $countries = $query
            ->orderBy('name')
            ->paginate(50)
            ->withQueryString();

        $regions = Country::select('region')
            ->whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        return view('countries.index', compact('countries', 'regions'));
    }
}