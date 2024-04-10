@extends('layouts.app')

@section('title_meta_info')
    <title>{{ $person['name'] }} | {{ config('app.name') }}</title>
    <meta name="description" content="Learn more about the peron - {{ $person['name'] }}" />
    <link rel="canonical" href="{{ route('person.show', $person['id']) }}" />
@endsection

@section('content')
    <div class="px-8 py-8">
        <div class="container mx-auto">
            <a href="{{ route('home') }}"
                class="tracking-wider text-gray-800 hover:text-cyan-800 focus:text-cyan-800 focus:outline-none focus:ring-0">Home</a>
            <span class="text-gray-600">&raquo;</span>
            <span class="capitalize tracking-wider text-gray-600">{{ $person['name'] }}</span>
        </div>
    </div>

    <div class="my-16 px-8">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-3 lg:grid-cols-4">
                <div>
                    <img src="{{ $person['profile_picture'] }}" alt="{{ $person['name'] }}"
                        class="mx-auto block rounded-md shadow-md" />

                    <div class="my-6 flex gap-6">
                        @foreach ($person['social_media'] as $platform => $link)
                            <a href="{{ $link }}" target="_blank" rel="noopener"
                                title="View {{ $person['name'] }} on {{ $platform }}">
                                <img src="/images/{{ $platform }}-logo.svg" alt="{{ $platform }}"
                                    class="h-12 w-12">
                            </a>
                        @endforeach
                    </div>

                    <div class="my-6 text-sm leading-6 tracking-wider lg:text-base lg:leading-8">
                        <div class="font-medium text-gray-500">Date of Birth</div>
                        <div class="font-semibold text-gray-800">{{ $person['date_of_birth'] }}</div>
                    </div>

                    <div class="my-6 text-sm leading-6 tracking-wider lg:text-base lg:leading-8">
                        <div class="font-medium text-gray-500">Place of Birth</div>
                        <div class="font-semibold text-gray-800">{{ $person['place_of_birth'] }}</div>
                    </div>

                    <div class="my-6 text-sm leading-6 tracking-wider lg:text-base lg:leading-8">
                        <div class="font-medium text-gray-500">Gender</div>
                        <div class="font-semibold text-gray-800">{{ $person['gender'] }}</div>
                    </div>

                    <div class="my-6 text-sm leading-6 tracking-wider lg:text-base lg:leading-8">
                        <div class="font-medium text-gray-500">Alternative Names</div>
                        @foreach ($person['alternative_names'] as $name)
                            <div class="font-semibold text-gray-800">{{ $name }}</div>
                        @endforeach
                    </div>
                </div>

                <div class="w-full md:col-span-2 lg:col-span-3">
                    <h1 class="text-xl font-bold uppercase tracking-wider text-gray-800 md:text-3xl">
                        {{ $person['name'] }}
                    </h1>

                    <p class="mt-8 text-sm leading-6 tracking-wider text-gray-700 lg:text-lg lg:leading-8">
                        {{ $person['biography'] }}
                    </p>

                    <h2 class="mt-6 font-semibold tracking-wider text-gray-700 lg:text-lg lg:leading-8">Acting Career</h2>

                    <div class="my-6 grid grid-cols-2 gap-8 sm:grid-cols-3 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                        @foreach ($person['cast'] as $cast)
                            <div class="overflow-hidden rounded-md bg-white shadow-md shadow-gray-400">
                                <a href="{{ $cast['tv_movie_link'] }}">
                                    @if ($cast['poster_path'])
                                        <img src="{{ $cast['poster_path'] }}" alt="{{ $cast['title'] }}"
                                            title="{{ $cast['title'] }}"
                                            class="transition duration-150 ease-in-out hover:opacity-75" />
                                    @else
                                        <div class="flex h-[750px] w-full items-center justify-center bg-cyan-950">
                                            <span
                                                class="rounded-md bg-white px-6 py-4 text-4xl font-bold uppercase text-cyan-950">
                                                No Image Available
                                            </span>
                                        </div>
                                    @endif
                                </a>

                                <div class="mt-2 px-4 py-2 leading-6 tracking-wider lg:leading-8">
                                    <span class="font-semibold">{{ $cast['played_as'] }}</span> in
                                    <a href="{{ $cast['tv_movie_link'] }}"
                                        class="text-base font-semibold tracking-wider text-gray-800 transition duration-200 ease-in-out hover:text-cyan-800 focus:text-cyan-800 focus:outline-none">
                                        {{ $cast['title'] }}
                                    </a>
                                    <span class="text-gray-600">({{ $cast['formatted_aired_or_released_on'] }})</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
