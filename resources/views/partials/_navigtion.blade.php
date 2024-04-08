<nav class="bg-cyan-950 px-8 py-4">
    <div class="container mx-auto">
        <div class="flex items-center justify-between">
            <a href="/" class="focus:outline-none focus:ring-0" title="Go to Home Page" name="go_to_home_page">
                <img src="https://www.themoviedb.org/assets/2/v4/logos/v2/blue_short-8e7b30f73a4020692ccca9c88bafe5dcb6f8a62a4c6bc55cd9ba82bb2cd95f6c.svg"
                    alt="{{ config('app.name') }}" title="{{ config('app.name') }}" class="block h-[20px] w-[154px]"
                    loading="eager" />
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
