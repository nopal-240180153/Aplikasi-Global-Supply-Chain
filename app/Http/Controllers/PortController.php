<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;

class PortController extends Controller
{
    /**
     * Tampilkan halaman peta pelabuhan
     */
    public function index(Request $request)
    {
        // Get all countries for filter dropdown
        $countries = Country::orderBy('name')->get();

        // Get filter values
        $search = $request->input('search');
        $countryId = $request->input('country');

        // Query ports with filters
        $ports = Port::with('country')
            ->when($search, function($query) use ($search) {
                $query->where('port_name', 'like', "%{$search}%");
            })
            ->when($countryId, function($query) use ($countryId) {
                $query->where('country_id', $countryId);
            })
            ->orderBy('port_name')
            ->get();

        // Count total
        $totalPorts = $ports->count();

        return view('ports.index', compact('ports', 'countries', 'totalPorts', 'search', 'countryId'));
    }

    /**
     * API endpoint untuk mendapatkan data pelabuhan dalam format JSON
     * Digunakan untuk update map secara dinamis via AJAX
     */
    public function getData(Request $request)
    {
        $search = $request->input('search');
        $countryId = $request->input('country');

        $ports = Port::with('country:id,name,flag')
            ->when($search, function($query) use ($search) {
                $query->where('port_name', 'like', "%{$search}%");
            })
            ->when($countryId, function($query) use ($countryId) {
                $query->where('country_id', $countryId);
            })
            ->select('id', 'port_name', 'country_id', 'latitude', 'longitude')
            ->get();

        return response()->json([
            'success' => true,
            'total' => $ports->count(),
            'ports' => $ports
        ]);
    }
}
