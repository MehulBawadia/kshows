<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Number;
use Illuminate\View\View;

class MovieController extends Controller
{
    /**
     * Display the movie details page of the given movieId.
     *
     * @param  string  $movieId
     */
    public function show($movieId): View
    {
        $movie = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url').'/movie/'.$movieId.'?append_to_response=credits,alternative_titles')
            ->json();
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
            'cast' => collect($movie['credits']['cast'])->take(12),
            'crew' => collect($movie['credits']['crew'])->take(12),
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
