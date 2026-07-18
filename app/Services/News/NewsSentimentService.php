<?php

namespace App\Services\News;

use App\Models\PositiveWord;
use App\Models\NegativeWord;
use Illuminate\Support\Facades\Cache;

class NewsSentimentService
{
    protected array $positiveWords = [];
    protected array $negativeWords = [];

    public function __construct()
    {
        // Cache lexicon selama 24 jam untuk performa
        $this->positiveWords = Cache::remember('positive_words_lexicon', 86400, function () {
            return PositiveWord::pluck('word')->map(fn($w) => strtolower($w))->toArray();
        });

        $this->negativeWords = Cache::remember('negative_words_lexicon', 86400, function () {
            return NegativeWord::pluck('word')->map(fn($w) => strtolower($w))->toArray();
        });
    }

    /**
     * Analyze sentiment dari text berita menggunakan lexicon-based approach
     * 
     * @param string $text - Teks berita (title + description)
     * @return array ['sentiment' => 'Positive|Negative|Neutral', 'score' => int]
     */
    public function analyze(string $text): array
    {
        $text = strtolower($text);
        $words = $this->tokenize($text);

        $positiveCount = 0;
        $negativeCount = 0;

        // Hitung jumlah kata positif dan negatif
        foreach ($words as $word) {
            if (in_array($word, $this->positiveWords)) {
                $positiveCount++;
            }
            
            if (in_array($word, $this->negativeWords)) {
                $negativeCount++;
            }
        }

        // Hitung sentiment score
        return $this->calculateSentiment($positiveCount, $negativeCount);
    }

    /**
     * Tokenize text menjadi array kata
     * 
     * @param string $text
     * @return array
     */
    protected function tokenize(string $text): array
    {
        // Hapus karakter khusus, hanya ambil huruf dan spasi
        $text = preg_replace('/[^a-z\s]/', '', $text);
        
        // Split by space dan filter empty
        return array_filter(explode(' ', $text), fn($w) => strlen($w) > 2);
    }

    /**
     * Hitung sentiment berdasarkan jumlah kata positif dan negatif
     * 
     * @param int $positiveCount
     * @param int $negativeCount
     * @return array
     */
    protected function calculateSentiment(int $positiveCount, int $negativeCount): array
    {
        $totalWords = $positiveCount + $negativeCount;

        // Jika tidak ada kata sentiment yang ditemukan
        if ($totalWords === 0) {
            return [
                'sentiment' => 'Neutral',
                'score' => 50,
                'positive_count' => 0,
                'negative_count' => 0
            ];
        }

        // Hitung selisih
        $difference = $positiveCount - $negativeCount;
        
        // Tentukan sentiment
        if ($difference > 0) {
            // Lebih banyak kata positif
            $sentiment = 'Positive';
            $score = min(100, 50 + ($difference * 10)); // Scale: 50-100
        } elseif ($difference < 0) {
            // Lebih banyak kata negatif
            $sentiment = 'Negative';
            $score = max(0, 50 + ($difference * 10)); // Scale: 0-50
        } else {
            // Jumlah sama
            $sentiment = 'Neutral';
            $score = 50;
        }

        return [
            'sentiment' => $sentiment,
            'score' => $score,
            'positive_count' => $positiveCount,
            'negative_count' => $negativeCount
        ];
    }

    /**
     * Clear cache lexicon (untuk refresh setelah update database)
     */
    public static function clearCache(): void
    {
        Cache::forget('positive_words_lexicon');
        Cache::forget('negative_words_lexicon');
    }
}