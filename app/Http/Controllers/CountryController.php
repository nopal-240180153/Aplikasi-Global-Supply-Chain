<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Watchlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $userWatchlists = Watchlist::where('user_id', Auth::id())
            ->pluck('country_id')
            ->toArray();

        return view('countries.index', compact('countries', 'regions', 'userWatchlists'));
    }

    public function show($id)
    {
        $country = Country::findOrFail($id);
        
        $economy = \App\Models\EconomyData::where('country_id', $id)->latest('year')->first();
        $weather = \App\Models\WeatherLog::where('country_id', $id)->latest('recorded_at')->first();
        $exchange = \App\Models\ExchangeRate::where('country_id', $id)->latest('recorded_at')->first();
        $risk = \App\Models\RiskScore::where('country_id', $id)->latest('calculated_at')->first();
        $news = \App\Models\NewsArticle::where('country_id', $id)->latest('published_at')->take(5)->get();
        $ports = \App\Models\Port::where('country_id', $id)->get();
        
        $isFavorited = Auth::check()
            ? \App\Models\Watchlist::where('user_id', Auth::id())->where('country_id', $id)->exists()
            : false;

        return view('countries.show', compact(
            'country', 
            'economy', 
            'weather', 
            'exchange', 
            'risk', 
            'news', 
            'ports',
            'isFavorited'
        ));
    }
}