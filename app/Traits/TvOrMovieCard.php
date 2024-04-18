<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Number;

trait TvOrMovieCard
{
    use GenresList;

    /**
     * Prepare the movie tv show details from the provided type and data.
     */
    protected function prepareMovieOrTvDetails(string $type, array $data): array
    {
        $details = [];

        foreach ($data as $tvOrMovie) {
            $details[] = [
                'id' => $tvOrMovie['id'],
                'type' => $type,
                'route_link' => route("{$type}.show", $tvOrMovie['id']),
                'name' => $type === 'movie' ? $tvOrMovie['title'] : $tvOrMovie['name'],
                'has_poster_image' => $tvOrMovie['poster_path'] ? true : false,
                'poster_image' => 'https://image.tmdb.org/t/p/w500/'.$tvOrMovie['poster_path'],
                'released_date' => $type === 'movie'
                    ? Carbon::parse($tvOrMovie['release_date'])->format('D jS M, Y')
                    : Carbon::parse($tvOrMovie['first_air_date'])->format('l jS M, Y'),
                'vote_average' => Number::percentage($tvOrMovie['vote_average'] * 10),
                'genres' => $this->filterGenres($type, $tvOrMovie['genre_ids']),
            ];
        }

        return collect($details)->filter(function ($tvMovie) {
            return $tvMovie['has_poster_image'] === true;
        })->toArray();
    }

    /**
     * From the provided genres list, filter the cached genres.
     */
    private function filterGenres(string $type, array $genreIds): string
    {
        $genresList = $type === 'movie' ? $this->moviesGenre() : $this->tvShowsGenre();

        return $genresList->only($genreIds)->sort()->implode(', ');
    }
}
