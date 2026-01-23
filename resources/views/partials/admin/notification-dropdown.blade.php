{{-- resources/views/partials/admin/notification-dropdown.blade.php --}}

@php
use App\Models\Notification;

$notifications = Notification::where('id_pengguna', auth()->id())
->latest()
->limit(10)
->get();

$unreadCount = Notification::where('id_pengguna', auth()->id())
->where('is_read', 0)
->count();
@endphp

<div
    class="relative"
    x-data="{ dropdownOpen: false }"
    @click.away="dropdownOpen = false">

    <!-- 🔔 BUTTON -->
    <button @click="dropdownOpen = !dropdownOpen"
        class="relative flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 hover:bg-gray-100 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800">

        {{-- UNREAD BADGE --}}
        @if($unreadCount > 0)
        <span class="absolute top-0 right-0 flex items-center justify-center rounded-full bg-red-500 text-xs text-white h-5 w-5">
            {{ $unreadCount }}
        </span>
        @endif

        <!-- ICON -->
        <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20">
            <path d="M10 1.5a5.5 5.5 0 0 0-5.5 5.5v3.5L3 13v1h14v-1l-1.5-2.5V7A5.5 5.5 0 0 0 10 1.5z" />
        </svg>
    </button>

    <!-- 📦 DROPDOWN -->
    <div x-show="dropdownOpen" x-transition
        class="absolute right-0 mt-3 w-[320px] overflow-hidden rounded-xl border border-gray-200 bg-white shadow-lg dark:border-gray-800 dark:bg-gray-900"
        style="display: none">

        <!-- HEADER -->
        <div class="flex items-center justify-between border-b px-4 py-2 dark:border-gray-800">
            <span class="text-sm font-semibold text-gray-800 dark:text-white">Notifikasi</span>
            @if($unreadCount > 0)
            <button class="text-xs text-blue-600 pl-3 hover:underline" @click.prevent="fetch('/admin/notifications/mark-all-read', { method: 'POST', headers: {'X-CSRF-TOKEN':'{{ csrf_token() }}'} }).then(()=>dropdownOpen=false);">
                Tandai semua dibaca
            </button>
            @endif
        </div>

        <!-- LIST -->
        <ul class="max-h-80 overflow-y-auto">
            @forelse($notifications as $notif)
            <li>
                <a href="#" onclick="readNotification({{ $notif->id_notification }})"
                    class="flex items-center gap-3 px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-800 {{ $notif->is_read ? 'opacity-70' : 'font-medium' }}">

                    <!-- ICON -->
                    <div class="flex h-8 w-8 items-center justify-center rounded-full
                        @if($notif->type==='pembayaran') bg-green-100 text-green-600
                        @elseif($notif->type==='pemesanan') bg-blue-100 text-blue-600
                        @elseif($notif->type==='jadwal') bg-orange-100 text-orange-600
                        @else bg-gray-200 text-gray-600 @endif">
                        🔔
                    </div>

                    <!-- TITLE + TIME -->
                    <div class="flex-1 text-sm">
                        <p>{{ $notif->title }}</p>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $notif->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            </li>
            @empty
            <li class="p-4 text-center text-sm text-gray-500">Tidak ada notifikasi</li>
            @endforelse
        </ul>

        <!-- FOOTER -->
        <div class="border-t px-4 py-2 text-center dark:border-gray-800">
            <a href="{{ route('admin.notifications.index') }}" class="text-sm text-blue-600 hover:underline">
                Lihat semua notifikasi
            </a>
        </div>
    </div>
</div>

<script>
    function readNotification(id) {
        fetch(`/admin/notifications/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        }).then(res => res.json()).then(res => {
            if (res.redirect) {
                window.location.href = res.redirect;
            } else {
                window.location.reload();
            }
        });
    }
</script>