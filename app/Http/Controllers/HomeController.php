<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the home page.
     */
    public function index(): View
    {
        $popularMovies = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url').'/movie/popular')
            ->json()['results'];

        $startDate = today()->subDays(30)->toDateString();
        $endDate = today()->toDateString();

        $nowPlaying = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url').'/discover/movie?include_adult=false&sort_by=popularity.desc&release_date.gte='.$startDate.'&release_date.lte='.$endDate)
            ->json()['results'];

        $genresArray = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url').'/genre/movie/list')
            ->json()['genres'];
        $genres = collect($genresArray)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });

        return view('welcome')->with([
            'popularMovies' => $popularMovies,
            'nowPlaying' => $nowPlaying,
            'genres' => $genres,
        ]);
    }
}
