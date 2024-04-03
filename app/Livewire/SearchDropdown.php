<?php

namespace App\Livewire;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Livewire\Component;

class SearchDropdown extends Component
{
    /**
     * The search query instance.
     *
     * @var string
     */
    public $search = '';

    /**
     * The search results instance.
     *
     * @var array
     */
    public $searchResults = [];

    /**
     * Get the search results.
     */
    public function getSearchResults(): void
    {
        if (strlen($this->search) > 2) {
            $movies = $this->performSearch('movie');
            $movieResults = $this->formatDetails($movies, 'movie');

            $tv = $this->performSearch('tv');
            $tvResults = $this->formatDetails($tv, 'tv');

            $this->searchResults = collect($movieResults)->merge($tvResults)->take(24)->values();
        }
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.search-dropdown', [
            'search' => $this->search,
            'searchResults' => $this->searchResults,
        ]);
    }

    /**
     * Perform the search at the TMDB end.
     *
     * @param  string  $name
     */
    protected function performSearch($type): array
    {
        $filters = $this->prepareSearchQuery();

        return Http::withToken(config('services.tmdb.token'))
            ->get(config('services.tmdb.base_url')."/search/{$type}?{$filters}")
            ->json()['results'];
    }

    /**
     * Prepare the search query filters.
     */
    protected function prepareSearchQuery(): string
    {
        $movieFilter = [
            'query' => $this->search,
            'language' => 'en-US',
            'region' => 'KR',
        ];

        return collect($movieFilter)->implode(function ($value, $index) {
            return "{$index}={$value}";
        }, '&');
    }

    /**
     * Format the details of the movie or tv show.
     *
     * @param  array  $data
     * @param  string  $type
     */
    protected function formatDetails($data, $type): Collection
    {
        return collect($data)->filter(function ($item) {
            return $item['original_language'] === 'ko';
        })->map(function ($item) use ($type) {
            if (! isset($item['title'])) {
                $item['title'] = $item['name'];
            }
            $item['type'] = $type;
            $item['endpoint_url'] = route('search.show', ['type' => $type, 'id' => $item['id']]);

            return $item;
        })->values();
    }
}
