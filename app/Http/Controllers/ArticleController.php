<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     * Display a listing of published articles
     */
    public function index(Request $request)
    {
        $query = Article::with('author')
            ->published()
            ->latest('published_at');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->paginate(9);

        $categories = Article::published()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        $popularArticles = Article::published()
            ->orderByDesc('views')
            ->take(5)
            ->get();

        return view('articles.index', compact('articles', 'categories', 'popularArticles'));
    }

    /**
     * Display the specified article
     */
    public function show($slug)
    {
        $article = Article::with('author')
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Increment views
        $article->incrementViews();

        // Related articles (same category)
        $relatedArticles = Article::published()
            ->where('category', $article->category)
            ->where('id', '!=', $article->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }
}
