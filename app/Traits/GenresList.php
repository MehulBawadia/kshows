<?php

namespace App\Traits;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

trait GenresList
{
    /**
     * Get the list of genres for the movies, and remember it forever
     * in the cache.
     */
    protected function moviesGenre() : Collection
    {
        return cache()->rememberForever('moviesGenre', function () {
            $genresArray = $this->fetchGenres('movie');

            return $this->prepareGenresList($genresArray);
        });
    }

    /**
     * Get the list of genres for the tv shows, and remember it forever
     * in the cache.
     */
    protected function tvShowsGenre()
    {
        return cache()->rememberForever('tvShowsGenre', function () {
            $genresArray = $this->fetchGenres('tv');

            return $this->prepareGenresList($genresArray);
        });
    }

    /**
     * Fetch the genres from the TMDB endpoint for the give type.
     *
     * @param  string  $type
     */
    protected function fetchGenres($type) : array
    {
        return Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url') . "/genre/{$type}/list")
            ->json()['genres'];
    }

    /**
     * Prepare the formatted list of the genres from the
     * provided genres array.
     *
     * @param  array  $genresArray
     */
    protected function prepareGenresList($genresArray) : Collection
    {
        return collect($genresArray)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });
    }
}
