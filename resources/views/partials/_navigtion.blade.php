<nav class="bg-cyan-950 px-8 py-4">
    <div class="container mx-auto">
        <div class="flex items-center justify-between">
            <a href="{{ route('home') }}" class="group flex items-center focus:outline-none focus:ring-0"
                title="Go to Home Page" name="go_to_home_page">
                <img src="/images/logo.svg" alt="{{ config('app.name') }}" title="{{ config('app.name') }}"
                    class="block w-9" loading="eager" />
                <span
                    class="ml-2 text-2xl font-bold text-cyan-400 transition duration-200 ease-in-out group-hover:text-cyan-300">
                    {{ config('app.name') }}
                </span>
            </a>

            <div class="hidden gap-4 lg:flex">
                <a href="{{ route('home') }}"
                    class="tracking-wider text-gray-100 transition duration-200 ease-in-out hover:text-cyan-400 focus:text-cyan-400 focus:outline-none focus:ring-0"
                    title="Go to Movies page" name="go_to_movies_page">Movies</a>
                <a href="{{ route('tv.index') }}"
                    class="tracking-wider text-gray-100 transition duration-200 ease-in-out hover:text-cyan-400 focus:text-cyan-400 focus:outline-none focus:ring-0"
                    title="Go to TV Shows page" name="go_to_tv_shows_page">TV Shows</a>

                <livewire:search-dropdown />
            </div>

            <div class="relative lg:hidden" x-data="{
                open: false,
                toggle() {
                    if (this.open) {
                        return this.close()
                    }
            
                    this.$refs.button.focus()
            
                    this.open = true
                },
                close(focusAfter) {
                    if (!this.open) return
            
                    this.open = false
            
                    focusAfter && focusAfter.focus()
                }
            }"
                x-on:keydown.escape.prevent.stop="close($refs.button)" x-id="['dropdown-button']" class="relative">
                <button x-show="!open" class="w-8 rounded-md bg-white" x-ref="button" x-on:click="toggle()"
                    :aria-expanded="open" :aria-controls="$id('dropdown-button')" type="button">
                    <img src="/images/mobile-menu-bars.svg" alt="Mobile Menu" />
                </button>

                <button x-show="open" class="w-8 rounded-md bg-white" x-ref="button" x-on:click="toggle()"
                    :aria-expanded="open" :aria-controls="$id('dropdown-button')" type="button">
                    <img src="/images/mobile-menu-close.svg" alt="Mobile Menu" />
                </button>

                <div class="absolute right-0 rounded bg-cyan-950 px-3 py-3" x-show="open">
                    <a href="{{ route('home') }}"
                        class="my-2 block tracking-wider text-gray-100 transition duration-200 ease-in-out hover:text-cyan-400 focus:text-cyan-400 focus:outline-none focus:ring-0"
                        title="Go to Movies page" name="go_to_movies_page">Movies</a>
                    <a href="{{ route('tv.index') }}"
                        class="my-2 block tracking-wider text-gray-100 transition duration-200 ease-in-out hover:text-cyan-400 focus:text-cyan-400 focus:outline-none focus:ring-0"
                        title="Go to TV Shows page" name="go_to_tv_shows_page">TV Shows</a>

                    <livewire:search-dropdown />
                </div>
            </div>
        </div>
    </div>
</nav>
