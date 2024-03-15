@extends('layouts.app')

@section('title_meta_info')
    <title>Sorted list of movies or series | {{ config('app.name') }}</title>
    <meta name="description" content="A list of movies and series sorted by user votes and poularity" />
    <link rel="canonical" href="{{ route('home') }}" />
@endsection

@section('content')
    <div class="px-8 py-16">
        <div class="container mx-auto">
            <h2 class="text-xl font-bold uppercase tracking-wider text-gray-800">Popular Movies</h2>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($popularMovies as $movie)
                    <x-movie-card :movie="$movie" :genres="$genres" />
                @endforeach
            </div>
        </div>
    </div>

    <div class="px-8 py-16">
        <div class="container mx-auto">
            <h2 class="text-xl font-bold uppercase tracking-wider text-gray-800">Now Playing</h2>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($nowPlaying as $movie)
                    <x-movie-card :movie="$movie" :genres="$genres" />
                @endforeach
            </div>
        </div>
    </div>
@endsection
