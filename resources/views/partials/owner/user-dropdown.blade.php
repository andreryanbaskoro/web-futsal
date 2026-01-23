@php
use Illuminate\Support\Facades\Auth;

$user = Auth::user();
$avatar = '/images/user/default.png'; // ganti jika sudah ada avatar

$menuItems = [
[
'text' => 'Edit Profile',
'path' => route('owner.profile.index'),
],
];
@endphp

<div
    class="relative"
    x-data="{
        dropdownOpen: false,
        toggleDropdown() { this.dropdownOpen = !this.dropdownOpen },
        closeDropdown() { this.dropdownOpen = false }
    }"
    @click.away="closeDropdown()">
    <!-- User Button -->
    <button
        type="button"
        @click.prevent="toggleDropdown()"
        class="flex items-center text-gray-700 dark:text-gray-400">

        <span class="mr-1 font-medium text-theme-sm">
            {{ $user->nama ?? 'Admin' }}
        </span>

        <!-- Chevron -->
        <svg
            class="w-5 h-5 transition-transform duration-200"
            :class="{ 'rotate-180': dropdownOpen }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown -->
    <div
        x-show="dropdownOpen"
        x-transition
        style="display: none;"
        class="absolute right-0 z-50 mt-[17px] w-[260px] rounded-2xl
               border border-gray-200 bg-white p-3 shadow-theme-lg
               dark:border-gray-800 dark:bg-gray-dark">
        <!-- User Info -->
        <div>
            <span class="block font-medium text-theme-sm text-gray-700 dark:text-gray-300">
                {{ $user->nama }}
            </span>
            <span class="block mt-0.5 text-theme-xs text-gray-500 dark:text-gray-400">
                {{ $user->email }}
            </span>
        </div>

        <!-- Menu -->
        <ul class="pt-4 pb-3 border-b border-gray-200 dark:border-gray-800">
            @foreach ($menuItems as $item)
            <li>
                <a
                    href="{{ $item['path'] }}"
                    class="flex items-center gap-3 rounded-lg px-3 py-2
                               text-theme-sm font-medium text-gray-700
                               hover:bg-gray-100
                               dark:text-gray-400 dark:hover:bg-white/5">
                    {{ $item['text'] }}
                </a>
            </li>
            @endforeach
        </ul>

        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                @click="closeDropdown()"
                class="mt-3 flex w-full items-center gap-3 rounded-lg px-3 py-2
                       text-theme-sm font-medium text-gray-700
                       hover:bg-gray-100
                       dark:text-gray-400 dark:hover:bg-white/5">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1
                             a3 3 0 01-3 3H6a3 3 0 01-3-3V7
                             a3 3 0 013-3h4a3 3 0 013 3v1" />
                </svg>
                Sign Out
            </button>
        </form>
    </div>
</div>