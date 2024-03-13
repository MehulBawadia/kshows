<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        @yield('title_meta_info')

        <meta name="theme-color" content="#22d3ee" />

        @vite('resources/css/app.css')
    </head>

    <body class="bg-gray-200 antialiased">
        @include('partials._navigtion')

        @yield('content')
    </body>

</html>
