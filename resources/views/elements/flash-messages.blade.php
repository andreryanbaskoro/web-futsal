<div class="space-y-1">

    {{-- Success --}}
    @if (session('success'))
    <div
        x-data="{ open: true, init() { setTimeout(() => this.open = false, 60000) } }"
        x-init="init()"
        x-show="open"
        x-transition
        class="flex items-start justify-between w-80 max-w-full rounded-md border-l-4 border-green-500 bg-green-50 p-4 text-green-700 shadow-lg mb-4"
    >
        <div class="flex-1 text-sm">
            {{ session('success') }}
        </div>
        <button
            @click="open = false"
            class="ml-4 text-green-700 hover:text-green-900"
        >
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

    {{-- Error --}}
    @if ($errors->any())
    <div
        x-data="{ open: true, init() { setTimeout(() => this.open = false, 60000) } }"
        x-init="init()"
        x-show="open"
        x-transition
        class="flex flex-col w-80 max-w-full rounded-md border-l-4 border-red-500 bg-red-50 p-4 text-red-700 shadow-lg mb-4"
    >
        <div class="flex justify-between items-start text-sm mb-2">
            <span>Terjadi beberapa kesalahan:</span>
            <button
                @click="open = false"
                class="text-red-700 hover:text-red-900"
            >
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ul class="list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Optional: Error session --}}
    @if (session('error'))
    <div
        x-data="{ open: true, init() { setTimeout(() => this.open = false, 60000) } }"
        x-init="init()"
        x-show="open"
        x-transition
        class="flex items-start justify-between w-80 max-w-full rounded-md border-l-4 border-red-500 bg-red-50 p-4 text-red-700 shadow-lg"
    >
        <div class="flex-1 text-sm">
            {{ session('error') }}
        </div>
        <button
            @click="open = false"
            class="ml-4 text-red-700 hover:text-red-900"
        >
            <i class="fas fa-times"></i>
        </button>
    </div>
    @endif

</div>
