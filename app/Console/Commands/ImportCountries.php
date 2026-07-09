<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportCountries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'countries:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import countries from REST Countries API';

    /**
     * Execute the console command.
     */
    public function handle(): int
{
    $response = \Illuminate\Support\Facades\Http::withToken(env('REST_COUNTRIES_API_KEY'))
        ->acceptJson()
        ->get(env('REST_COUNTRIES_BASE_URL').'/countries/v5');

    dd($response->json()['data']['objects'][0]);

    return self::SUCCESS;
}
}