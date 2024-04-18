@extends('layouts.app')

@section('title_meta_info')
    <title>TV Shows | {{ config('app.name') }}</title>
    <meta name="description" content="List of all the TV Shows" />
    <link rel="canonical" href="{{ route('tv.index', ['pageNumber' => $page]) }}" />
@endsection

@section('content')
    <div class="px-8 py-8">
        <div class="container mx-auto">
            <a href="{{ route('home') }}"
                class="tracking-wider text-gray-800 hover:text-cyan-800 focus:text-cyan-800 focus:outline-none focus:ring-0">Home</a>
            <span class="text-gray-600">&raquo;</span>
            <span class="capitalize tracking-wider text-gray-600">TV Shows</span>
        </div>
    </div>

    <div class="px-8 py-16">
        <div class="container mx-auto">
            <h2 class="text-xl font-bold uppercase tracking-wider text-gray-800">Popular TV Shows</h2>

            <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
                @foreach ($tvShows as $show)
                    <x-movie-tv-show-card :movie-tv-show="$show" />
                @endforeach
            </div>

            <div class="mt-8 flex w-full items-center justify-between">
                @if ($page > 1)
                    <a href="{{ route('tv.index', ['pageNumber' => $page - 1]) }}" class="inline">Previous Page</a>
                @endif

                @if ($page < $totalPages)
                    <a href="{{ route('tv.index', ['pageNumber' => (int) ($page + 1)]) }}" class="inline">Next Page</a>
                @endif
            </div>
        </div>
    </div>
@endsection
