@if ($user['has_profile_picture'])
    <div class="overflow-hidden rounded-md bg-white shadow-md shadow-gray-400">
        <a href="{{ route('person.show', $user['id']) }}">
            <img src="{{ $user['profile_picture'] }}" alt="{{ $user['name'] }}" title="{{ $user['name'] }}"
                class="w-full transition duration-150 ease-in-out hover:opacity-75" width="300" height="450" />
        </a>

        <div class="mt-2 px-4 py-2">
            <a href="#"
                class="text-base font-semibold tracking-wider text-gray-800 transition duration-200 ease-in-out hover:text-cyan-800 focus:text-cyan-800 focus:outline-none">{{ $user['name'] }}</a>

            <div class="mt-1 flex items-center text-sm tracking-wider text-gray-600">
                {{ $user['role'] }}
            </div>
        </div>
    </div>
@endif
