<div class="relative mt-3 md:mt-0">
    <form wire:submit.prevent.debounce.500ms="getSearchResults">
        <input wire:model.debouce.500ms="search" type="text"
            class="w-64 rounded-full bg-gray-200 py-1 pl-8 text-sm focus:outline-none focus:ring"
            placeholder="Search (Press '/' to focus)" @focus="isOpen = true" @keydown="isOpen = true"
            @keydown.escape.window="isOpen = false" @keydown.shift.tab="isOpen = false" x-ref="search"
            @keydown.window="
            if (event.keyCode == 191) {
                event.preventDefault();
                $refs.search.focus();
            }
        " />
    </form>

    <div class="absolute top-0 ml-2 flex h-full items-center">
        <svg class="w-4" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M15.796 15.811 21 21m-3-10.5a7.5 7.5 0 1 1-15 0 7.5 7.5 0 0 1 15 0Z" stroke="#1f2937"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </div>

    <div wire:loading class="spinner right-0 top-0 mr-4 mt-3"></div>

    @if (strlen($search) >= 2)
        <div class="absolute z-50 mt-2 w-64 rounded bg-gray-800">
            <ul>
                @forelse ($searchResults as $result)
                    @if ($result['poster_path'])
                        <li class="border-b border-gray-400">
                            <a href="{{ route('movie.show', $result['id']) }}"
                                class="flex items-center px-3 py-2 text-gray-200 hover:bg-gray-600"
                                @if ($loop->last) @keydown.tab="isOpen = false" @endif>
                                <img src="https://image.tmdb.org/t/p/w92/{{ $result['poster_path'] }}"
                                    alt="{{ $result['title'] }}" class="w-8">

                                <span class="ml-4">{{ $result['title'] }}</span>
                            </a>
                        </li>
                    @endif
                @empty
                    <li class="px-3 py-2">No results for "{{ $search }}"</li>
                @endforelse
            </ul>
        </div>
    @endif
</div>
