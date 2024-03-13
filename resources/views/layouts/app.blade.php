<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        @yield('title_meta_info')

        @vite('resources/css/app.css')
    </head>

    <body class="bg-gray-200 antialiased">
        @yield('content')
    </body>

</html>
