<?php

namespace App\Http\Controllers;

use App\Repositories\EconomyRepository;

class EconomyController extends Controller
{
    protected EconomyRepository $repository;

    public function __construct(EconomyRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $economies = $this->repository->paginate(20);

        return view(
            'economy.index',
            compact('economies')
        );
    }
}