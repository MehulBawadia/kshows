<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Number;
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
     * @param  int  $pageNumber
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
     * Display the details of the given tv show id.
     *
     * @param  int  $tvShowId
     */
    public function show($tvShowId) : View
    {
        $tvShow = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url') . "/tv/{$tvShowId}?append_to_response=credits,videos,images")
            ->json();
        $tvShow = $this->formatTvShowDetails($tvShow);

        return view('tv-shows.show', [
            'tvShow' => $tvShow,
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

    /**
     * Format the details of the given tvShow.
     *
     * @param  array  $tvShow
     */
    private function formatTvShowDetails($tvShow) : array
    {
        return [
            'id' => $tvShow['id'],
            'title' => $tvShow['name'],
            'poster_path' => 'https://image.tmdb.org/t/p/w500/' . $tvShow['poster_path'],
            'overview' => $tvShow['overview'],
            'first_air_date' => Carbon::parse($tvShow['first_air_date'])->format('l jS F, Y'),
            'vote_average' => Number::percentage($tvShow['vote_average'] * 10),
            'genres' => $this->getGenres($tvShow['genres'])->implode(', '),
            'cast' => $this->getCastCrewDetails($tvShow['credits']['cast']),
            'crew' => $this->getCastCrewDetails($tvShow['credits']['crew']),
            // 'cast' => collect($tvShow['credits']['cast']),
            // 'crew' => collect($tvShow['credits']['crew']),
        ];
    }

    /**
     * Format the cast and crew details that are required.
     *
     * @param  array $data
     */
    protected function getCastCrewDetails($data) : array
    {
        return collect($data)->map(function ($castCrew) {
            $hasProfilePicture = isset ($castCrew['profile_path']) && $castCrew['profile_path'] !== null;

            // Get the name intials of the cast or crew which will be
            // used at the time of displaying the profile picture.
            preg_match_all('/\b\w/', $castCrew['name'], $matches);

            return [
                'id' => $castCrew['id'],
                'name' => $castCrew['name'],
                'has_profile_picture' => $hasProfilePicture,
                'profile_picture' => $hasProfilePicture ? "https://image.tmdb.org/t/p/w300/" . $castCrew['profile_path'] : null,
                'role' => isset ($castCrew['character']) ? $castCrew['character'] : $castCrew['job'],
                'name_initials' => isset ($matches[0]) && $matches[0] ? implode('.', $matches[0]) : "--",
            ];
        })->toArray();
    }

    /**
     * Get the genres from the given array of genres.
     *
     * @param  array  $genres
     */
    private function getGenres($genres) : Collection
    {
        $data = [];
        foreach ($genres as $genre) {
            $data[] = $genre['name'];
        }

        return collect($data)->sort();
    }
}
