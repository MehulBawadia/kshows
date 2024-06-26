@extends('layouts.app')

@section('title_meta_info')
    <title>{{ $tvShow['title'] }} | {{ config('app.name') }}</title>
    <meta name="description" content="Learn more about the movie {{ $tvShow['title'] }}" />
    <link rel="canonical" href="{{ route('tv.show', $tvShow['id']) }}" />
@endsection

@section('content')
    <div class="px-8 py-8">
        <div class="container mx-auto">
            <a href="{{ route('home') }}"
                class="tracking-wider text-gray-800 hover:text-cyan-800 focus:text-cyan-800 focus:outline-none focus:ring-0">Home</a>
            <span class="text-gray-600">&raquo;</span>
            <a href="{{ route('tv.index') }}"
                class="tracking-wider text-gray-800 hover:text-cyan-800 focus:text-cyan-800 focus:outline-none focus:ring-0">TV
                Shows</a>
            <span class="text-gray-600">&raquo;</span>
            <span class="capitalize tracking-wider text-gray-600">{{ $tvShow['title'] }}</span>
        </div>
    </div>

    <div class="my-2 px-8 sm:my-4">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <img src="{{ $tvShow['poster_path'] }}" alt="{{ $tvShow['title'] }}"
                    class="mx-auto block w-full rounded-md" />

                <div class="w-full md:col-span-2">
                    <h1 class="text-xl font-bold uppercase tracking-wider text-gray-800 md:text-3xl">
                        {{ $tvShow['title'] }}
                    </h1>

                    <div class="mt-2 flex flex-wrap items-center text-sm text-gray-600">
                        <svg class="w-4 fill-current text-cyan-800" viewBox="0 0 24 24">
                            <g data-name="Layer 2">
                                <path
                                    d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z"
                                    data-name="star" />
                            </g>
                        </svg>
                        <span class="ml-1">{{ $tvShow['vote_average'] }}</span>
                        <span class="mx-2">|</span>
                        <span>{{ $tvShow['first_air_date'] }}</span>
                        <span class="mx-2">|</span>
                        <span>{{ $tvShow['genres'] }}</span>
                    </div>

                    <div class="my-6 text-sm leading-6 tracking-wider lg:text-base lg:leading-8">
                        <div class="font-semibold text-gray-700">Alternative Titles:</div>
                        <div class="text-gray-800">
                            {{ implode(' | ', $tvShow['alternative_titles']) }}
                        </div>
                    </div>

                    <div class="my-6 text-sm leading-6 tracking-wider lg:text-base lg:leading-8">
                        <span class="font-medium text-gray-700">Number of Seasons:</span>
                        <span class="ml-1 font-semibold text-gray-800">{{ $tvShow['total_seasons'] }}</span>
                    </div>

                    <div class="my-6 text-sm leading-6 tracking-wider lg:text-base lg:leading-8">
                        <span class="font-medium text-gray-700">Number of Episodes:</span>
                        <span class="ml-1 font-semibold text-gray-800">{{ $tvShow['total_episodes'] }}</span>
                    </div>

                    <p
                        class="mt-8 text-justify text-sm leading-6 tracking-wider text-gray-700 md:text-base lg:text-lg lg:leading-8">
                        {{ $tvShow['overview'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="my-6 px-8 py-8">
        <div class="container mx-auto">
            <h2 class="text-base font-semibold tracking-wider text-gray-800 sm:text-xl">Cast</h2>

            <div class="mt-6 grid grid-cols-1 gap-8 xxs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($tvShow['cast'] as $cast)
                    <x-user-card :user="$cast" />
                @endforeach
            </div>
        </div>
    </div>

    <div class="my-6 px-8 py-8">
        <div class="container mx-auto">
            <h2 class="text-base font-semibold tracking-wider text-gray-800 md:text-xl">Episodes</h2>

            <table class="mt-6 w-full overflow-hidden rounded text-gray-500 shadow">
                <thead class="bg-gray-50 text-xs uppercase text-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-center tracking-wider">Episode Number</th>
                        <th class="px-6 py-3 text-left tracking-wider">Overview</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tvShow['episodes'] as $episode)
                        <tr class="border-b bg-white">
                            <td class="px-6 py-4 text-center text-gray-900">
                                {{ $episode['number'] }}
                            </td>
                            <td class="px-6 py-4 text-justify capitalize leading-6">
                                @if ($episode['overview'])
                                    <div class="mb-4 text-sm leading-7 tracking-widest text-gray-800">
                                        {{ $episode['overview'] }}
                                    </div>
                                @endif
                                <div>
                                    <span class="text-sm leading-7 tracking-wider">
                                        Season: {{ $episode['season_number'] }}
                                    </span>
                                    <span class="mx-4 text-sm leading-7 tracking-wider">|</span>
                                    <time class="text-sm leading-8 tracking-widest">
                                        {{ $episode['air_date'] }}
                                    </time>
                                    <span class="mx-4 text-sm leading-7 tracking-wider">|</span>
                                    <span class="text-sm leading-7 tracking-wider">
                                        {{ $episode['human_readable_time_length'] }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="my-6 px-8 py-8">
        <div class="container mx-auto">
            <h2 class="text-base font-semibold tracking-wider text-gray-800 sm:text-xl">Crew Members</h2>

            @if ($tvShow['crew'])
                <div class="mt-6 grid grid-cols-1 gap-8 xxs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                    @foreach ($tvShow['crew'] as $crew)
                        <x-user-card :user="$crew" />
                    @endforeach
                </div>
            @else
                <div class="mt-6 text-sm leading-6 tracking-wider text-gray-600 lg:text-base lg:leading-8">
                    No Records Available for the Crew, at the moment.
                </div>
            @endif
        </div>
    </div>
@endsection
