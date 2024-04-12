<?php

namespace App\Traits;

trait CastCrewDetails
{
    /**
     * Format the cast and crew details that are required.
     *
     * @param  array  $data
     */
    protected function getCastCrewDetails($data): array
    {
        return collect($data)->map(function ($castCrew) {
            $hasProfilePicture = isset($castCrew['profile_path']) && $castCrew['profile_path'] !== null;

            // Get the name intials of the cast or crew which will be
            // used at the time of displaying the profile picture.
            preg_match_all('/\b\w/', $castCrew['name'], $matches);

            return [
                'id' => $castCrew['id'],
                'name' => $castCrew['name'],
                'has_profile_picture' => $hasProfilePicture,
                'profile_picture' => $hasProfilePicture ? 'https://image.tmdb.org/t/p/w300/'.$castCrew['profile_path'] : null,
                'role' => isset($castCrew['character']) ? $castCrew['character'] : $castCrew['job'],
                'name_initials' => isset($matches[0]) && $matches[0] ? implode('.', $matches[0]) : '--',
            ];
        })->toArray();
    }
}
