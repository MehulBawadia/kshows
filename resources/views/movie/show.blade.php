@extends('layouts.app')

@section('title_meta_info')
    <title>{{ $movie['title'] }} | {{ config('app.name') }}</title>
    <meta name="description" content="Learn more about the movie {{ $movie['title'] }}" />
    <link rel="canonical" href="{{ route('movie.show', $movie['id']) }}" />
@endsection

@section('content')
    <div class="px-8 py-8">
        <div class="container mx-auto">
            <a href="{{ route('home') }}"
                class="tracking-wider text-gray-800 hover:text-cyan-800 focus:text-cyan-800 focus:outline-none focus:ring-0">Home</a>
            <span class="text-gray-600">&raquo;</span>
            <span class="capitalize tracking-wider text-gray-600">{{ $movie['title'] }}</span>
        </div>
    </div>

    <div class="my-16 px-8">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
                <div>
                    <img src="{{ $movie['poster_path'] }}" alt="{{ $movie['title'] }}"
                        class="mx-auto block rounded-md shadow-md" />

                    <div class="my-6 text-sm leading-6 tracking-wider lg:text-base lg:leading-8">
                        <div class="font-medium text-gray-500">Alternative Titles</div>
                        <div class="font-semibold text-gray-800">
                            @forelse ($movie['alternative_titles'] as $title)
                                <span class="block">{{ $title }}</span>
                            @empty
                                No alternative titles added.
                            @endforelse
                        </div>
                    </div>
                </div>

                <div class="w-full md:col-span-2">
                    <h1 class="text-xl font-bold uppercase tracking-wider text-gray-800 md:text-3xl">
                        {{ $movie['title'] }}
                    </h1>

                    <div class="mt-2 flex flex-wrap items-center text-sm text-gray-600">
                        <svg class="w-4 fill-current text-cyan-800" viewBox="0 0 24 24">
                            <g data-name="Layer 2">
                                <path
                                    d="M17.56 21a1 1 0 01-.46-.11L12 18.22l-5.1 2.67a1 1 0 01-1.45-1.06l1-5.63-4.12-4a1 1 0 01-.25-1 1 1 0 01.81-.68l5.7-.83 2.51-5.13a1 1 0 011.8 0l2.54 5.12 5.7.83a1 1 0 01.81.68 1 1 0 01-.25 1l-4.12 4 1 5.63a1 1 0 01-.4 1 1 1 0 01-.62.18z"
                                    data-name="star" />
                            </g>
                        </svg>
                        <span class="ml-1">{{ $movie['vote_average'] }}</span>
                        <span class="mx-2">|</span>
                        <span>{{ $movie['release_date'] }}</span>
                        <span class="mx-2">|</span>
                        <span>{{ $movie['genres'] }}</span>
                    </div>

                    <p
                        class="mt-8 text-justify text-sm leading-6 tracking-wider text-gray-700 md:text-base lg:text-lg lg:leading-8">
                        {{ $movie['overview'] }}</p>

                    <div class="my-12">
                        <h2 class="text-base font-semibold tracking-wider text-gray-800 md:text-xl">Cast</h2>

                        <div
                            class="mt-6 grid grid-cols-2 gap-8 sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            @foreach ($movie['cast'] as $cast)
                                <x-user-card :user="$cast" />
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="px-8 py-16">
        <div class="container mx-auto">
            <h2 class="text-base font-semibold tracking-wider text-gray-800 md:text-xl">Crew Members</h2>

            <div class="mt-6 grid grid-cols-2 gap-8 sm:grid-cols-3 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($movie['crew'] as $crew)
                    <x-user-card :user="$crew" />
                @endforeach
            </div>
        </div>
    </div>
@endsection
