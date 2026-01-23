{{-- resources/views/admin/notifications/index.blade.php --}}

@extends('layouts.admin') {{-- ganti dengan layout admin kamu --}}

@section('title', 'Notifications')

@section('content')
<div class="container mx-auto p-6">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-white">Notifications</h1>

        <button
            id="markAllReadBtn"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
            Mark All as Read
        </button>
    </div>

    @if ($notifications->isEmpty())
    <div class="text-center text-gray-500 dark:text-gray-400">
        No notifications yet.
    </div>
    @else
    <ul class="space-y-2">
        @foreach ($notifications as $notification)
        <li class="p-4 border rounded-lg flex justify-between items-start
                       {{ $notification->is_read ? 'bg-gray-50 dark:bg-gray-800' : 'bg-white dark:bg-gray-700' }}">

            <div class="flex flex-col">
                <span class="font-semibold text-gray-800 dark:text-white">{{ $notification->title }}</span>
                <span class="text-gray-600 dark:text-gray-300 text-sm mt-1">{{ $notification->message }}</span>
                <span class="text-gray-400 dark:text-gray-400 text-xs mt-1">
                    {{ $notification->created_at->diffForHumans() }} | Type: {{ $notification->type }}
                </span>
            </div>

            <div class="flex flex-col items-end gap-2">
                @if (!$notification->is_read)
                <button
                    class="markAsReadBtn px-3 py-1 text-xs bg-green-500 text-white rounded hover:bg-green-600"
                    data-id="{{ $notification->id_notification }}">
                    Mark as Read
                </button>
                @else
                <span class="text-green-600 text-xs font-medium">Read</span>
                @endif

                @if ($notification->url)
                <a href="{{ $notification->url }}" class="text-blue-600 text-xs hover:underline">Go</a>
                @endif
            </div>

        </li>
        @endforeach
    </ul>
    @endif
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Mark single notification as read
        document.querySelectorAll('.markAsReadBtn').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;
                fetch(`{{ url('admin/notifications/mark-as-read') }}/${id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            location.reload(); // reload untuk update badge & list
                        }
                    });
            });
        });

        // Mark all notifications as read
        document.getElementById('markAllReadBtn')?.addEventListener('click', function() {
            fetch('{{ url('
                    admin / notifications / mark - all - as - read ') }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                    })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload(); // reload untuk update badge & list
                    }
                });
        });

    });
</script>

@endsection