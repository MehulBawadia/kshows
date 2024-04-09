<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Number;
use Illuminate\View\View;

class PersonController extends Controller
{
    /**
     * A list of genders.
     *
     * @var array
     */
    public $gender = [
        0 => 'Not Set / Not Specified',
        1 => 'Female',
        2 => 'Male',
        3 => 'Non Binary',
    ];

    /**
     * Display the movie details page of the given personId.
     *
     * @param  string  $personId
     */
    public function show($personId) : View
    {
        $person = Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url') . "/person/{$personId}?append_to_response=combined_credits,external_ids")
            ->json();
        $person = $this->preparePersonDetails($person);

        return view('person.show')->with([
            'person' => $person,
        ]);
    }

    /**
     * Prepare the details of the given person.
     *
     * @param  array $person
     */
    protected function preparePersonDetails($person) : array
    {
        return [
            'id' => $person['id'],
            'name' => $person['name'],
            'profile_picture' => "https://image.tmdb.org/t/p/w500{$person['profile_path']}",
            'gender' => $this->gender[$person['gender']],
            'alternative_names' => $person['also_known_as'],
            'date_of_birth' => Carbon::parse($person['birthday'])->format('l jS F, Y'),
            'place_of_birth' => $person['place_of_birth'],
            'biography' => $person['biography'],
            'cast' => $this->prepareMovieTvShowCredits($person['combined_credits']['cast']),
            'social_media' => $this->prepareSocialMediaLinks($person['external_ids']),
        ];
    }

    /**
     * Prepare the list of all the movies and tv shows based on the credits
     * that are provided.
     *
     * @param  array $credits
     */
    protected function prepareMovieTvShowCredits($credits) : array
    {
        return collect($credits)->map(function ($cast) {
            return [
                'id' => $cast['id'],
                'title' => $cast['media_type'] === 'movie' ? $cast['title'] : $cast['name'],
                'overview' => $cast['overview'],
                'poster_path' => "https://image.tmdb.org/t/p/w500{$cast['poster_path']}",
                'played_as' => $cast['character'],
                'aired_or_released_on' => $cast['media_type'] === 'movie'
                    ? $cast['release_date']
                    : $cast['first_air_date'],
                'formatted_aired_or_released_on' => $cast['media_type'] === 'movie'
                    ? Carbon::parse($cast['release_date'])->format('jS F, Y')
                    : Carbon::parse($cast['first_air_date'])->format('jS F, Y'),
                'vote_average' => Number::percentage($cast['vote_average'] * 10),
                'media_type' => $cast['media_type'],
                'tv_movie_link' => $cast['media_type'] === 'movie'
                    ? route('movie.show', $cast['id'])
                    : route('tv.show', $cast['id']),
            ];
        })
            ->sortByDesc('aired_or_released_on')
            ->filter(function ($cast) {
                if ($cast['aired_or_released_on'] && $cast['poster_path']) {
                    return $cast;
                }
            })->values()->toArray();
    }

    /**
     * Prepare the social media links based on the given links.
     *
     * @param  array  $links
     */
    protected function prepareSocialMediaLinks($links) : array
    {
        $platforms = ['facebook', 'twitter', 'instagram',];
        $socials = [];
        foreach ($platforms as $platform) {
            if ($links[$platform . '_id']) {
                $socials[$platform] = "https://{$platform}.com/{$links[$platform . '_id']}";
            }
        }

        return $socials;
    }
}
