<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class TvShowController extends Controller
{
    /**
     * The list of all the tv shows instance holder.
     *
     * @var array
     */
    public $tvShowsList = [];

    /**
     * The API response holder for the tv shows endpoint.
     *
     * @var array
     */
    public $tvResponse = [];

    /**
     * A list of unwanted genres.
     *
     * @var array
     */
    public $unwatedGenres = [];

    /**
     * Display the tv shows.
     *
     * @param integer $pageNumber
     * @return \Illuminate\View\View
     */
    public function index($pageNumber = 1) : View
    {
        $genresArray = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url') . '/genre/tv/list')
            ->json()['genres'];
        $genres = collect($genresArray)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });

        $this->unwatedGenres = $genres->filter(function ($genre) {
            if (in_array($genre, ['Animation', 'Kids', 'News', 'Reality', 'Documentary', 'Talk'])) {
                return $genre;
            }
        });

        $tvShows = $this->getTvShows($pageNumber);

        return view('tv-shows.index', [
            'tvShows' => $tvShows,
            'page' => $this->tvResponse['page'],
            'totalPages' => $this->tvResponse['total_pages'],
            'genres' => $genres,
        ]);
    }

    /**
     * Filter out the tv shows based on some crieterias.
     * For more filtering criteria, refer the url given below.
     *
     * @param  int  $pageNumber
     *
     * @link https://developer.themoviedb.org/reference/discover-tv
     */
    protected function getTvShows($pageNumber) : array
    {
        $tvFilter = [
            'air_date.lte' => today()->format('Y-m-d'),
            'page' => $pageNumber,
            'sort_by' => 'first_air_date.desc',
            'vote_count.gte' => 1,
            'watch_region' => 'KR',
            'with_original_language' => 'ko',
            'without_genres' => $this->unwatedGenres->keys()->implode('|'),
        ];
        $tvFilter = collect($tvFilter)->implode(function ($value, $index) {
            return "{$index}={$value}";
        }, '&');

        $this->tvResponse = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url') . "/discover/tv?{$tvFilter}")
            ->json();

        $this->tvShowsList = array_merge($this->tvShowsList, $this->tvResponse['results']);

        $list = collect($this->tvShowsList)->transform(function ($show) {
            $show['first_air_date'] = Carbon::parse($show['first_air_date'])->format('jS F, Y');
            return $show;
        })->values()->toArray();

        return $list;
    }
}
