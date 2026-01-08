@php
use App\Helpers\MenuHelper;
$menus = MenuHelper::getMenuItems();
@endphp

<aside class="fixed top-0 left-0 w-64 h-screen bg-white dark:bg-gray-900 border-r border-gray-200 dark:border-gray-800">
    
    <!-- Logo -->
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
        <h1 class="text-lg font-bold text-gray-800 dark:text-white">
            Admin Panel
        </h1>
    </div>

    <!-- Menu -->
    <nav class="mt-4">
        <ul class="space-y-1">
            @foreach ($menus as $menu)
                <li>
                    @if ($menu['name'] === 'Logout')
                        <!-- Logout (POST) -->
                        <form action="{{ $menu['path'] }}" method="POST">
                            @csrf
                            <button
                                class="w-full flex items-center gap-3 px-6 py-2 text-left
                                text-red-600 hover:bg-red-100 dark:hover:bg-red-900">
                                <i class="{{ $menu['icon'] }} w-5 text-center"></i>
                                <span>{{ $menu['name'] }}</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ $menu['path'] }}"
                           class="flex items-center gap-3 px-6 py-2
                           hover:bg-gray-100 dark:hover:bg-gray-800
                           {{ MenuHelper::isActive($menu['path']) ? 'bg-gray-100 dark:bg-gray-800 font-semibold' : '' }}">
                            
                            <!-- Icon -->
                            <i class="{{ $menu['icon'] }} w-5 text-center"></i>

                            <!-- Text -->
                            <span>{{ $menu['name'] }}</span>
                        </a>
                    @endif
                </li>
            @endforeach
        </ul>
    </nav>
</aside>
