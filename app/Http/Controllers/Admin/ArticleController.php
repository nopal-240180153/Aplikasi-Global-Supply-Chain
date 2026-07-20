<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    /**
     * Display a listing of articles
     */
    public function index(Request $request)
    {
        $query = Article::with('author')->latest();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $articles = $query->paginate(10);

        $stats = [
            'total' => Article::count(),
            'published' => Article::where('status', 'published')->count(),
            'draft' => Article::where('status', 'draft')->count(),
            'archived' => Article::where('status', 'archived')->count(),
        ];

        $categories = Article::whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('admin.articles.index', compact('articles', 'stats', 'categories'));
    }

    /**
     * Show the form for creating a new article
     */
    public function create()
    {
        $categories = Article::whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Store a newly created article in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        // Generate slug
        $validated['slug'] = Str::slug($validated['title']);
        
        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $count = 1;
        while (Article::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $count;
            $count++;
        }

        // Parse tags
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = [];
        }

        // Set author
        $validated['user_id'] = auth()->id();

        // Set published_at if status is published
        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        Article::create($validated);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dibuat!');
    }

    /**
     * Show the form for editing the specified article
     */
    public function edit(Article $article)
    {
        $categories = Article::whereNotNull('category')
            ->distinct()
            ->pluck('category');

        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Update the specified article in storage
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'string', 'max:500'],
            'content' => ['required', 'string'],
            'category' => ['nullable', 'string', 'max:100'],
            'tags' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url', 'max:500'],
            'status' => ['required', 'in:draft,published,archived'],
        ]);

        // Update slug if title changed
        if ($validated['title'] !== $article->title) {
            $validated['slug'] = Str::slug($validated['title']);
            
            // Ensure unique slug (except current article)
            $originalSlug = $validated['slug'];
            $count = 1;
            while (Article::where('slug', $validated['slug'])->where('id', '!=', $article->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $count;
                $count++;
            }
        }

        // Parse tags
        if (!empty($validated['tags'])) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = [];
        }

        // Set published_at when changing status from draft to published
        if ($validated['status'] === 'published' && $article->status !== 'published') {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil diperbarui!');
    }

    /**
     * Remove the specified article from storage
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return redirect()
            ->route('admin.articles.index')
            ->with('success', 'Artikel berhasil dihapus!');
    }

    /**
     * Publish article
     */
    public function publish(Article $article)
    {
        $article->update([
            'status' => 'published',
            'published_at' => $article->published_at ?? now(),
        ]);

        return back()->with('success', 'Artikel berhasil dipublikasikan!');
    }

    /**
     * Archive article
     */
    public function archive(Article $article)
    {
        $article->update([
            'status' => 'archived',
        ]);

        return back()->with('success', 'Artikel berhasil diarsipkan!');
    }
}
