<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\NewsArticle;

class NewsController extends Controller
{
    public function index()
    {
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