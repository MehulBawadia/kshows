<nav class="bg-cyan-950 px-8 py-4">
    <div class="container mx-auto">
        <div class="flex items-center justify-between">
            <a href="/" class="group flex items-center focus:outline-none focus:ring-0" title="Go to Home Page"
                name="go_to_home_page">
                <img src="/images/logo.svg" alt="{{ config('app.name') }}" title="{{ config('app.name') }}"
                    class="block w-9" loading="eager" />
                <span
                    class="ml-2 text-2xl font-bold text-cyan-400 transition duration-200 ease-in-out group-hover:text-cyan-300">
                    {{ config('app.name') }}
                </span>
            </a>

            <div class="flex gap-4">
                <a href="{{ route('home') }}"
                    class="tracking-wider text-gray-100 transition duration-200 ease-in-out hover:text-cyan-400 focus:text-cyan-400 focus:outline-none focus:ring-0"
                    title="Go to Movies page" name="go_to_movies_page">Movies</a>
                <a href="{{ route('tv.index') }}"
                    class="tracking-wider text-gray-100 transition duration-200 ease-in-out hover:text-cyan-400 focus:text-cyan-400 focus:outline-none focus:ring-0"
                    title="Go to TV Shows page" name="go_to_tv_shows_page">TV Shows</a>
                <a href="#"
                    class="tracking-wider text-gray-100 transition duration-200 ease-in-out hover:text-cyan-400 focus:text-cyan-400 focus:outline-none focus:ring-0"
                    title="Go to People page" name="go_to_people_page">People</a>

                <livewire:search-dropdown />
            </div>
        </div>
    </div>
</nav>
