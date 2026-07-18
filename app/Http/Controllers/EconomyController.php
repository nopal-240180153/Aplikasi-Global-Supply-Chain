<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Repositories\EconomyRepository;
use Illuminate\Http\Request;

class EconomyController extends Controller
{
    protected EconomyRepository $repository;

    public function __construct(EconomyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $region = $request->input('region');
        
        $economies = $this->repository->paginate(20, $search, $region);

        // Get unique regions for filter
        $regions = Country::select('region')
            ->whereNotNull('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        return view(
            'economy.index',
            compact('economies', 'regions')
        );
    }
}