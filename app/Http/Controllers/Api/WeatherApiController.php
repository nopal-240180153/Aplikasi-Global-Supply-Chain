<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\WeatherRepository;

class WeatherApiController extends Controller
{
    protected WeatherRepository $repository;

    public function __construct(WeatherRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return response()->json([
            'success' => true,
            'total' => $this->repository->count(),
            'data' => $this->repository->getAll()
        ]);
    }
}