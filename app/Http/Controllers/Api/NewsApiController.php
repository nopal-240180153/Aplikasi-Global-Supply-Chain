<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsArticle;
use Illuminate\Http\Request;

class NewsApiController extends Controller
{
    /**
     * Display a listing of news articles
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = NewsArticle::with('country');

            // Search by title or summary
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhere('summary', 'like', "%{$search}%");
                });
            }

            // Filter by country
            if ($request->filled('country_id')) {
                $query->where('country_id', $request->country_id);
            }

            // Filter by country name
            if ($request->filled('country')) {
                $country = $request->country;
                $query->whereHas('country', function($q) use ($country) {
                    $q->where('name', 'like', "%{$country}%");
                });
            }

            // Filter by sentiment
            if ($request->filled('sentiment')) {
                $query->where('sentiment', $request->sentiment);
            }

            // Filter by source
            if ($request->filled('source')) {
                $query->where('source', 'like', "%{$request->source}%");
            }

            // Filter by date range
            if ($request->filled('from_date')) {
                $query->whereDate('published_at', '>=', $request->from_date);
            }
            if ($request->filled('to_date')) {
                $query->whereDate('published_at', '<=', $request->to_date);
            }

            // Sort
            $sortBy = $request->get('sort_by', 'published_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 10);
            $news = $query->paginate($perPage);

            // Transform data
            $data = $news->map(function($article) {
                return [
                    'id' => $article->id,
                    'country_id' => $article->country_id,
                    'country_name' => $article->country->name ?? 'Unknown',
                    'country_code' => $article->country->code ?? null,
                    'title' => $article->title,
                    'summary' => $article->summary,
                    'description' => $article->description,
                    'source' => $article->source,
                    'url' => $article->url,
                    'image_url' => $article->image_url,
                    'sentiment' => $article->sentiment,
                    'sentiment_score' => (float) $article->sentiment_score,
                    'published_at' => $article->published_at?->format('Y-m-d H:i:s'),
                    'created_at' => $article->created_at->format('Y-m-d H:i:s'),
                ];
            });

            return response()->json([
                'success' => true,
                'message' => 'News articles retrieved successfully',
                'data' => $data,
                'meta' => [
                    'total' => $news->total(),
                    'per_page' => $news->perPage(),
                    'current_page' => $news->currentPage(),
                    'last_page' => $news->lastPage(),
                    'from' => $news->firstItem(),
                    'to' => $news->lastItem(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve news articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified news article
     * 
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $article = NewsArticle::with('country')->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'News article retrieved successfully',
                'data' => [
                    'id' => $article->id,
                    'country_id' => $article->country_id,
                    'country_name' => $article->country->name ?? 'Unknown',
                    'country_code' => $article->country->code ?? null,
                    'title' => $article->title,
                    'summary' => $article->summary,
                    'description' => $article->description,
                    'source' => $article->source,
                    'url' => $article->url,
                    'image_url' => $article->image_url,
                    'sentiment' => $article->sentiment,
                    'sentiment_score' => (float) $article->sentiment_score,
                    'published_at' => $article->published_at?->format('Y-m-d H:i:s'),
                    'created_at' => $article->created_at->format('Y-m-d H:i:s'),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'News article not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
