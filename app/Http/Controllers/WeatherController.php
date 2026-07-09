<?php

namespace App\Http\Controllers;

use App\Repositories\WeatherRepository;

class WeatherController extends Controller
{
    protected WeatherRepository $repository;

    public function __construct(WeatherRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $weatherLogs = $this->repository->paginate(20);

        return view('weather.index', compact(
            'weatherLogs'
        ));
    }
}