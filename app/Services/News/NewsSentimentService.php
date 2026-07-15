<?php

namespace App\Services\News;

class NewsSentimentService
{
    protected array $positiveWords = [

        'growth',
        'profit',
        'stable',
        'success',
        'increase',
        'improve',
        'recovery',
        'positive'

    ];

    protected array $negativeWords = [

        'war',
        'crisis',
        'inflation',
        'disaster',
        'storm',
        'earthquake',
        'decline',
        'risk',
        'conflict',
        'recession'

    ];

    public function analyze(string $text): array
    {
        $text = strtolower($text);

        $positive = 0;
        $negative = 0;

        foreach ($this->positiveWords as $word) {

            if (str_contains($text, $word)) {

                $positive++;

            }

        }

        foreach ($this->negativeWords as $word) {

            if (str_contains($text, $word)) {

                $negative++;

            }

        }

        if ($negative > $positive) {

            return [

                'sentiment' => 'Negative',

                'score' => 100

            ];

        }

        if ($positive > $negative) {

            return [

                'sentiment' => 'Positive',

                'score' => 100

            ];

        }

        return [

            'sentiment' => 'Neutral',

            'score' => 50

        ];
    }
}