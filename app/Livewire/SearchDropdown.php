<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Livewire\Component;

class SearchDropdown extends Component
{
    public $search = '';
    public $searchResults = [];

    /**
     * Get the search results.
     */
    public function getSearchResults() : void
    {
        if (strlen($this->search) > 2) {
            $results = Http::withToken(config('services.tmdb.token'))
                ->get(config('services.tmdb.base_url') . '/search/movie?query=' . $this->search)
                ->json()['results'];

            $this->searchResults = collect($results)->take(12);
        }
    }

    /**
     * Render the component.
     */
    public function render() : View
    {
        return view('livewire.search-dropdown', [
            'search' => $this->search,
            'searchResults' => $this->searchResults,
        ]);
    }
}
