<div
    x-data="{
        useUrl: {{ old(
            'image_type',
            $lapangan->image_type ?? 'upload'
        ) === 'url' ? 'true' : 'false' }}
    }"
    class="space-y-4">

    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
        Gambar Lapangan
    </label>

    {{-- TOGGLE UPLOAD / URL --}}
    <div class="flex items-center gap-3 mb-2">
        {{-- UPLOAD --}}
        <label class="flex items-center gap-2 cursor-pointer">
            <input
                type="radio"
                name="image_type"
                value="upload"
                @change="useUrl = false"
                :checked="!useUrl"
                class="sr-only">
            <span
                :class="!useUrl
                    ? 'bg-brand-500 text-white'
                    : 'bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-white/50'"
                class="px-3 py-1 rounded-lg text-sm">
                Upload
            </span>
        </label>

        {{-- URL --}}
        <label class="flex items-center gap-2 cursor-pointer">
            <input
                type="radio"
                name="image_type"
                value="url"
                @change="useUrl = true"
                :checked="useUrl"
                class="sr-only">
            <span
                :class="useUrl
                    ? 'bg-brand-500 text-white'
                    : 'bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-white/50'"
                class="px-3 py-1 rounded-lg text-sm">
                URL
            </span>
        </label>
    </div>

    {{-- UPLOAD FILE --}}
    <div x-show="!useUrl" x-transition class="space-y-2">
        <input
            type="file"
            name="image_file"
            accept="image/*"
            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors
                   file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700
                   placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden
                   dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90
                   dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400" />

        @if(isset($lapangan) && ($lapangan->image_type ?? '') === 'upload')
        <img
            src="{{ asset('storage/' . $lapangan->image) }}"
            class="mt-2 h-32 rounded-lg border object-cover">
        @endif
    </div>

    {{-- URL INPUT --}}
    <div x-show="useUrl" x-transition class="space-y-2">
        <input
            type="url"
            name="image_url"
            value="{{ old('image_url', ($lapangan->image_type ?? '') === 'url' ? $lapangan->image : '') }}"
            placeholder="https://example.com/lapangan.jpg"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm
                   dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">

        @if(isset($lapangan) && ($lapangan->image_type ?? '') === 'url')
        <img
            src="{{ $lapangan->image }}"
            class="mt-2 h-32 rounded-lg border object-cover">
        @endif
    </div>

</div>