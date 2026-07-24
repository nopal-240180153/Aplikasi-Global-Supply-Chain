<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Port;
use App\Models\Country;
use Illuminate\Http\Request;

class PortController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        
        $query = Port::with('country')->orderBy('port_name');
        
        if ($search) {
            $query->where('port_name', 'like', "%{$search}%")
                  ->orWhere('port_code', 'like', "%{$search}%")
                  ->orWhereHas('country', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }
        
        $ports = $query->paginate(15)->appends(['search' => $search]);
        
        return view('admin.ports.index', compact('ports', 'search'));
    }

    public function create()
    {
        $countries = Country::orderBy('name')->get();
        return view('admin.ports.create', compact('countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_name' => 'required|string|max:255',
            'port_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'nullable|string|max:50',
        ]);

        Port::create($validated);

        return redirect()->route('admin.ports.index')->with('success', 'Data pelabuhan berhasil ditambahkan.');
    }

    public function edit(Port $port)
    {
        $countries = Country::orderBy('name')->get();
        return view('admin.ports.edit', compact('port', 'countries'));
    }

    public function update(Request $request, Port $port)
    {
        $validated = $request->validate([
            'country_id' => 'required|exists:countries,id',
            'port_name' => 'required|string|max:255',
            'port_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'type' => 'nullable|string|max:50',
        ]);

        $port->update($validated);

        return redirect()->route('admin.ports.index')->with('success', 'Data pelabuhan berhasil diperbarui.');
    }

    public function destroy(Port $port)
    {
        $port->delete();
        return redirect()->route('admin.ports.index')->with('success', 'Data pelabuhan berhasil dihapus.');
    }
}
