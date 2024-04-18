<?php

namespace App\Http\Controllers;

use App\Services\TMDB;
use App\Traits\GenresList;
use App\Traits\TvOrMovieCard;
use Illuminate\View\View;

class HomeController extends Controller
{
    use GenresList, TvOrMovieCard;

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
     * The unwanted genres list.
     *
     * @var array
     */
    public $unwantedGenres = [];

    /**
     * Display the home page.
     */
    public function index($pageNumber = 1): View
    {
        $genres = $this->moviesGenre();

        $this->unwantedGenres = $genres->filter(function ($genre) {
            if (in_array($genre, ['Animation', 'Kids', 'News', 'Reality', 'Documentary', 'Talk'])) {
                return $genre;
            }
        });

        $popularMovies = $this->getMovies($pageNumber);

        $popularMovies = $this->prepareMovieOrTvDetails('movie', $popularMovies);

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
            'api_key' => config('services.tmdb.api_key'),
            'page' => $pageNumber,
            'sort_by' => 'primary_release_date.desc',
            'vote_average.gte' => 0,
            'vote_average.lte' => 10,
            'with_original_language' => 'ko',
            'watch_region' => 'KR',
            'release_date.lte' => today()->format('Y-m-d'),
            'release_date.gte' => '1970-01-01',
            'vote_count.gte' => 3,
            'without_genres' => $this->unwantedGenres->keys()->implode('|'),
        ];
        $this->movieResponse = TMDB::movies($movieFilter);

        $this->moviesList = array_merge($this->moviesList, $this->movieResponse['results']);

        return $this->moviesList;
    }
}
