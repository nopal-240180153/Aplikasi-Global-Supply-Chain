<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\NewsArticle;
use App\Services\AutoSyncService;

class NewsController extends Controller
{
    public function index()
    {
        AutoSyncService::checkAndSync('News');

        $countries = Country::whereHas('newsArticles')->orderBy('name')->get();

        $countryId = request('country');

        $query = NewsArticle::with('country')
            ->latest('published_at');

        if ($countryId) {

            $query->where('country_id', $countryId);

        }

        $news = $query->paginate(15);

        return view('news.index', compact(

            'news',

            'countries',

            'countryId'

        ));
    }
}