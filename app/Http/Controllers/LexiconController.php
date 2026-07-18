<?php

namespace App\Http\Controllers;

use App\Models\PositiveWord;
use App\Models\NegativeWord;
use App\Services\News\NewsSentimentService;
use Illuminate\Http\Request;

class LexiconController extends Controller
{
    /**
     * Tampilkan halaman manage lexicon
     */
    public function index()
    {
        $positiveWords = PositiveWord::orderBy('word')->paginate(20, ['*'], 'positive_page');
        $negativeWords = NegativeWord::orderBy('word')->paginate(20, ['*'], 'negative_page');

        return view('admin.lexicon', compact('positiveWords', 'negativeWords'));
    }

    /**
     * Tambah kata positif
     */
    public function storePositive(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:100|unique:positive_words,word'
        ], [
            'word.required' => 'Kata harus diisi',
            'word.unique' => 'Kata sudah ada dalam daftar',
            'word.max' => 'Kata maksimal 100 karakter'
        ]);

        PositiveWord::create([
            'word' => strtolower(trim($request->word))
        ]);

        // Clear cache
        NewsSentimentService::clearCache();

        return redirect()
            ->route('admin.lexicon')
            ->with('success', 'Kata positif berhasil ditambahkan');
    }

    /**
     * Hapus kata positif
     */
    public function destroyPositive(PositiveWord $positiveWord)
    {
        $positiveWord->delete();
        
        // Clear cache
        NewsSentimentService::clearCache();

        return redirect()
            ->route('admin.lexicon')
            ->with('success', 'Kata positif berhasil dihapus');
    }

    /**
     * Tambah kata negatif
     */
    public function storeNegative(Request $request)
    {
        $request->validate([
            'word' => 'required|string|max:100|unique:negative_words,word'
        ], [
            'word.required' => 'Kata harus diisi',
            'word.unique' => 'Kata sudah ada dalam daftar',
            'word.max' => 'Kata maksimal 100 karakter'
        ]);

        NegativeWord::create([
            'word' => strtolower(trim($request->word))
        ]);

        // Clear cache
        NewsSentimentService::clearCache();

        return redirect()
            ->route('admin.lexicon')
            ->with('success', 'Kata negatif berhasil ditambahkan');
    }

    /**
     * Hapus kata negatif
     */
    public function destroyNegative(NegativeWord $negativeWord)
    {
        $negativeWord->delete();
        
        // Clear cache
        NewsSentimentService::clearCache();

        return redirect()
            ->route('admin.lexicon')
            ->with('success', 'Kata negatif berhasil dihapus');
    }
}
