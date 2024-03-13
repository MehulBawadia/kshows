@extends('layouts.app')

@section('title_meta_info')
    <title>Sorted list of movies or series | {{ config('app.name') }}</title>
    <meta name="description" content="A list of movies and series sorted by user votes and poularity" />
    <link rel="canonical" href="{{ route('home') }}" />
@endsection

@section('content')
    <h1>Hello World</h1>
@endsection
