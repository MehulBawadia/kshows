<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * The list of all the movies instance holder.
     *
     * @var array
     */
    public $moviesList = [];

    /**
     * The API response holder for the movies endpoint.
     *
     * @var array
     */
    public $movieResponse = [];

    /**
     * Display the home page.
     */
    public function index($pageNumber = 1): View
    {
        $popularMovies = $this->getMovies($pageNumber);

        $genresArray = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url').'/genre/movie/list')
            ->json()['genres'];
        $genres = collect($genresArray)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });

        return view('welcome')->with([
            'popularMovies' => $popularMovies,
            'page' => $this->movieResponse['page'],
            'totalPages' => $this->movieResponse['total_pages'],
            'genres' => $genres,
        ]);
    }

    /**
     * Filter out the movies based on some crieterias.
     * For more filtering criteria, refer the url given below.
     *
     * @param  int  $pageNumber
     *
     * @link https://developer.themoviedb.org/reference/discover-movie
     */
    protected function getMovies($pageNumber = 1): array
    {
        $movieFilter = [
            'page' => $pageNumber,
            'sort_by' => 'primary_release_date.desc',
            'vote_average.gte' => 0,
            'vote_average.lte' => 10,
            'with_original_language' => 'ko',
            'watch_region' => 'KR',
            'release_date.lte' => today()->format('Y-m-d'),
            'release_date.gte' => '1970-01-01',
            'vote_count.gte' => 3,
        ];
        $movieFilter = collect($movieFilter)->implode(function ($value, $index) {
            return "{$index}={$value}";
        }, '&');

        $this->movieResponse = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url')."/discover/movie?{$movieFilter}")
            ->json();

        $this->moviesList = array_merge($this->moviesList, $this->movieResponse['results']);

        return $this->moviesList;
    }
}
