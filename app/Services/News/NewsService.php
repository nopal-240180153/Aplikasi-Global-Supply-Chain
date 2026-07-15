<?php

namespace App\Services\News;

use Illuminate\Support\Facades\Http;

class NewsService
{
    public function getNews(string $country): array
    {
        try {

            $response = Http::timeout(60)

                ->retry(3, 2000)

                ->get(config('gnews.base_url'), [

                    'q' => $country,

                    'lang' => 'en',

                    'max' => 10,

                    'apikey' => config('gnews.api_key')

                ]);

            if (!$response->successful()) {

                return [];

            }

            return $response->json()['articles'] ?? [];

        } catch (\Throwable $e) {

            report($e);

            return [];

        }
    }
}