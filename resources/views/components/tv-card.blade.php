<div class="mt-8 overflow-hidden rounded-md bg-white shadow-md shadow-gray-400">
    <a href="{{ route('tv.show', $show['id']) }}">
        <img src="https://image.tmdb.org/t/p/w500/{{ $show['poster_path'] }}" alt="{{ $show['name'] }}"
            title="{{ $show['name'] }}"
            class="w-full transition duration-150 ease-in-out hover:scale-105 hover:opacity-75" />
    </a>

    <div class="mt-2 px-4 py-1 tracking-wider">
        <a href="{{ route('tv.show', $show['id']) }}"
            class="text-base font-semibold text-gray-800 transition duration-200 ease-in-out hover:text-cyan-800 focus:text-cyan-800 focus:outline-none xl:text-lg">{{ $show['name'] }}</a>

        <div class="mt-1 flex items-center text-sm text-gray-600">
            <svg class="w-4 fill-current text-cyan-800" viewBox="0 0 24 24">
                <g data-name="Layer 2">
                    <path
                        d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z"
                        data-name="star" />
                </g>
            </svg>
            <span class="ml-1">{{ \Illuminate\Support\Number::percentage($show['vote_average'] * 10) }}</span>
            <span class="mx-2">|</span>
            <span>{{ $show['first_air_date'] }}</span>
        </div>

        <div class="my-1">
            @foreach ($show['genre_ids'] as $index => $genre)
                <span class="text-sm text-gray-600">
                    {{ $genres->get($genre) }}
                </span>
            @endforeach
        </div>
    </div>
</div>
