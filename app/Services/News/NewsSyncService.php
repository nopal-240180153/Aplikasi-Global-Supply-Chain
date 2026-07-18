<?php

namespace App\Services\News;

use App\Models\Country;
use App\Repositories\NewsRepository;
use Carbon\Carbon;

class NewsSyncService
{
    protected NewsRepository $repository;

    protected NewsService $newsService;

    protected NewsSentimentService $sentimentService;

    public function __construct(
        NewsRepository $repository,
        NewsService $newsService,
        NewsSentimentService $sentimentService
    ) {
        $this->repository = $repository;
        $this->newsService = $newsService;
        $this->sentimentService = $sentimentService;
    }

    /**
     * Sinkronisasi berita seluruh negara
     */
    public function sync(): int
    {
        $total = 0;

        /*
        |--------------------------------------------------------------------------
        | Ambil hanya negara yang benar-benar diperlukan
        |--------------------------------------------------------------------------
        */

        $countries = Country::whereIn('name', [

            'Indonesia',
            'China',
            'Japan',
            'United States',
            'Singapore',
            'South Korea',
            'Germany',
            'Netherlands',
            'India',
            'Vietnam'

        ])->get();

        foreach ($countries as $country) {

            try {

                $articles = $this->newsService->getNews($country->name);

                if (empty($articles)) {
                    continue;
                }

                foreach ($articles as $article) {

                    $analysis = $this->sentimentService->analyze(

                        ($article['title'] ?? '') .
                        ' ' .
                        ($article['description'] ?? '')

                    );

                    $this->repository->updateOrCreate(

                        [

                            'url' => $article['url']

                        ],

                        [

                            'country_id' => $country->id,

                            'title' => $article['title'] ?? '-',

                            'source' => $article['source']['name'] ?? '-',

                            'image_url' => $article['image'] ?? null,

                            'description' => $article['description'] ?? null,

                            'summary' => $article['description'] ?? '-',

                            'sentiment' => $analysis['sentiment'],

                            'sentiment_score' => $analysis['score'],

                            'published_at' => isset($article['publishedAt'])
                                ? Carbon::parse($article['publishedAt'])
                                : now()

                        ]

                    );

                    $total++;

                }

            } catch (\Throwable $e) {

                report($e);

                continue;

            }

        }

        return $total;
    }
}