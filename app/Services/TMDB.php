<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TMDB
{
    /**
     * Fetch all the movies based on the given filter.
     */
    public static function movies(array $filter): array
    {
        return Http::withQueryParameters($filter)
            ->get(config('services.tmdb.base_url').'/discover/movie')
            ->json();
    }

    /**
     * Fetch the single movie details of the given movieId
     * and the filter paramters.
     */
    public static function movieDetails(int $movieId, array $filter): array
    {
        return Http::withQueryParameters($filter)
            ->get(config('services.tmdb.base_url')."/movie/{$movieId}")
            ->json();
    }

    /**
     * Fetch the tv shows based on the provided filter.
     */
    public static function tvShows(array $filter): array
    {
        return Http::withQueryParameters($filter)
            ->get(config('services.tmdb.base_url').'/discover/tv')
            ->json();
    }

    /**
     * Fetch the single tv show details of the given tvShowId
     * and the filter paramters.
     */
    public static function tvShowDetails(int $tvShowId, array $filter): array
    {
        return Http::withQueryParameters($filter)
            ->get(config('services.tmdb.base_url')."/tv/{$tvShowId}")
            ->json();
    }

    public static function seasonEpisodes($tvShowId, $seasonNumber = 1)
    {
        $apiKey = config('services.tmdb.api_key');
        $uri = "/tv/{$tvShowId}/season/{$seasonNumber}";

        return Http::get(config('services.tmdb.base_url')."{$uri}?api_key={$apiKey}")
            ->json()['episodes'];
    }

    /**
     * Fetch the individual person details of the given personId
     * and the filter paramters.
     *
     * @param  int  $personId
     */
    public static function personDetails($personId, array $filter): array
    {
        return Http::withQueryParameters($filter)
            ->get(config('services.tmdb.base_url')."/person/{$personId}")
            ->json();
    }
}
