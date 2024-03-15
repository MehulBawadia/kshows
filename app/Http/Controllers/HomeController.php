<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     */
    public function index() : View
    {
        $popularMovies = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url') . '/movie/popular')
            ->json()['results'];

        $genresArray = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url') . '/genre/movie/list')
            ->json()['genres'];
        $genres = collect($genresArray)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });

        return view("welcome")->with([
            'popularMovies' => $popularMovies,
            'genres' => $genres,
        ]);
    }
}
