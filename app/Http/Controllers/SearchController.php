<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Number;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Display the movie/tv details page of the given id.
     *
     * @param  string  $type
     * @param  string  $id
     */
    public function show($type, $id): View
    {
        $tvOrMovie = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url')."/{$type}/{$id}?append_to_response=credits,videos,images")
            ->json();
        $tvOrMovie = $this->formatDetails($tvOrMovie, $type);

        return view('search')->with([
            'type' => $type,
            'tvOrMovie' => $tvOrMovie,
        ]);
    }

    /**
     * Format the details of the given movie.
     *
     * @param  array  $tvOrMovie
     */
    private function formatDetails($tvOrMovie, $type): array
    {
        return [
            'id' => $tvOrMovie['id'],
            'title' => array_key_exists('title', $tvOrMovie) ? $tvOrMovie['title'] : $tvOrMovie['name'],
            'poster_path' => 'https://image.tmdb.org/t/p/w500/'.$tvOrMovie['poster_path'],
            'overview' => $tvOrMovie['overview'],
            'release_date' => $type === 'movie'
                ? Carbon::parse($tvOrMovie['release_date'])->format('l jS F, Y')
                : Carbon::parse($tvOrMovie['first_air_date'])->format('l jS F, Y'),
            'vote_average' => Number::percentage($tvOrMovie['vote_average'] * 10),
            'genres' => $this->getGenres($tvOrMovie['genres'])->implode(', '),
            'cast' => collect($tvOrMovie['credits']['cast']),
            'crew' => collect($tvOrMovie['credits']['crew']),
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
}
