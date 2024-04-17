<?php

namespace App\Http\Controllers;

use App\Services\TMDB;
use App\Traits\CastCrewDetails;
use App\Traits\GenresList;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Illuminate\View\View;

class TvShowController extends Controller
{
    use CastCrewDetails, GenresList;

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
    public function index($pageNumber = 1): View
    {
        $genres = $this->tvShowsGenre();

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
    public function show($tvShowId): View
    {
        $tvShowFilter = [
            'api_key' => config('services.tmdb.api_key'),
            'append_to_response' => 'credits,alternative_titles',
        ];
        $tvShow = TMDB::tvShowDetails($tvShowId, $tvShowFilter);
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
    protected function getTvShows($pageNumber): array
    {
        $tvFilter = [
            'api_key' => config('services.tmdb.api_key'),
            'air_date.lte' => today()->format('Y-m-d'),
            'page' => $pageNumber,
            'sort_by' => 'first_air_date.desc',
            'vote_count.gte' => 1,
            'watch_region' => 'KR',
            'with_original_language' => 'ko',
            'without_genres' => $this->unwatedGenres->keys()->implode('|'),
        ];
        $this->tvResponse = TMDB::tvShows($tvFilter);

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
    private function formatTvShowDetails($tvShow): array
    {
        return [
            'id' => $tvShow['id'],
            'title' => $tvShow['name'],
            'poster_path' => 'https://image.tmdb.org/t/p/w500/'.$tvShow['poster_path'],
            'overview' => $tvShow['overview'],
            'first_air_date' => Carbon::parse($tvShow['first_air_date'])->format('l jS F, Y'),
            'vote_average' => Number::percentage($tvShow['vote_average'] * 10),
            'status' => $tvShow['status'],
            'total_episodes' => $tvShow['number_of_episodes'],
            'total_seasons' => $tvShow['number_of_seasons'],
            'genres' => $this->getGenres($tvShow['genres'])->implode(', '),
            'cast' => $this->getCastCrewDetails($tvShow['credits']['cast']),
            'crew' => $this->getCastCrewDetails($tvShow['credits']['crew']),
            'episodes' => $this->prepareEpisodeDetails($tvShow['id'], $tvShow['seasons']),
            'alternative_titles' => $this->getAlternativeTitles($tvShow['alternative_titles']['results']),
        ];
    }

    /**
     * Prepare the necessary details of the given seasons.
     *
     * @param  array  $seasons
     */
    protected function prepareEpisodeDetails($tvShowId, $seasons): array
    {
        $episodes = [];
        foreach ($seasons as $season) {
            $seasonNumber = $season['season_number'];
            $episodes[$seasonNumber] = TMDB::seasonEpisodes($tvShowId, $seasonNumber);
        }

        $episodes = collect($episodes)->flatten(1)->filter(function ($data) {
            if ($data['season_number'] > 0) {
                return $data;
            }
        })->map(function ($episode, $number) {
            return [
                'id' => $episode['id'],
                'season_number' => $episode['season_number'],
                'number' => $episode['episode_number'],
                'air_date' => Carbon::parse($episode['air_date'])->format('l jS F, Y'),
                'overview' => $episode['overview'],
                'runtime' => $episode['runtime'],
                'formatted_length' => $time = ($episode['runtime'] ? date('H:i', mktime(0, $episode['runtime'])) : '00:00'),
                'human_readable_time_length' => $this->formatRuntime($time),
            ];
        })->filter();

        return $episodes->count() === 1 ? $episodes->first() : $episodes->toArray();
    }

    /**
     * Get the genres from the given array of genres.
     *
     * @param  array  $genres
     */
    private function getGenres($genres): Collection
    {
        $data = [];
        foreach ($genres as $genre) {
            $data[] = $genre['name'];
        }

        return collect($data)->sort();
    }

    /**
     * Format the run time into a human readable format.
     *
     * @param  string  $time
     */
    protected function formatRuntime($time): string
    {
        [$hours, $minutes] = explode(':', $time);
        $totalMinutes = $hours * 60 + $minutes;
        $convertedHours = floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;

        $output = ($convertedHours > 0 ? "$convertedHours hour ".($convertedHours > 1 ? 's' : '') : '')
            .($remainingMinutes > 0 ? (empty($output) ? '' : ' and ')."$remainingMinutes minute".($remainingMinutes > 1 ? 's' : '') : '');

        return $output;
    }

    /**
     * Get the alternative titles from the provided array.
     *
     * @param  array  $titles
     */
    private function getAlternativeTitles($titles): array
    {
        return collect($titles)->map(function ($title) {
            return $title['title'];
        })->toArray();
    }
}
