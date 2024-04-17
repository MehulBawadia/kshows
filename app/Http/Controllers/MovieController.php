<?php

namespace App\Http\Controllers;

use App\Services\TMDB;
use App\Traits\CastCrewDetails;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;
use Illuminate\View\View;

class MovieController extends Controller
{
    use CastCrewDetails;

    /**
     * Display the movie details page of the given movieId.
     *
     * @param  string  $movieId
     */
    public function show($movieId): View
    {
        $movieFilter = [
            'api_key' => config('services.tmdb.api_key'),
            'append_to_response' => 'credits,alternative_titles',
        ];
        $movie = TMDB::movieDetails($movieId, $movieFilter);
        $movie = $this->formatMovieDetails($movie);

        return view('movie.show')->with([
            'movie' => $movie,
        ]);
    }

    /**
     * Format the details of the given movie.
     *
     * @param  array  $movie
     */
    private function formatMovieDetails($movie): array
    {
        return [
            'id' => $movie['id'],
            'title' => $movie['title'],
            'poster_path' => 'https://image.tmdb.org/t/p/w500/'.$movie['poster_path'],
            'overview' => $movie['overview'],
            'release_date' => Carbon::parse($movie['release_date'])->format('l jS F, Y'),
            'vote_average' => Number::percentage($movie['vote_average'] * 10),
            'genres' => $this->getGenres($movie['genres'])->implode(', '),
            'cast' => $this->getCastCrewDetails($movie['credits']['cast']),
            'crew' => $this->getCastCrewDetails($movie['credits']['crew']),
            'alternative_titles' => $this->getAlternativeTitles($movie['alternative_titles']['titles']),
        ];
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
