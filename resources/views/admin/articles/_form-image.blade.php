<div x-data="{ useUrl: {{ isset($article) && str_starts_with($article->featured_image ?? '', 'http') ? 'true' : 'false' }} }" class="space-y-4">

    <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Gambar Unggulan</label>

    {{-- Toggle Upload / URL --}}
    <div class="flex items-center gap-3 mb-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="image_type" value="upload"
                @change="useUrl = false"
                :checked="!useUrl"
                class="sr-only">
            <span :class="!useUrl
                ? 'bg-brand-500 text-white'
                : 'bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-white/50'"
                class="px-3 py-1 rounded-lg text-sm">
                Upload
            </span>
        </label>
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="radio" name="image_type" value="url"
                @change="useUrl = true"
                :checked="useUrl"
                class="sr-only">
            <span :class="useUrl
                ? 'bg-brand-500 text-white'
                : 'bg-gray-200 dark:bg-white/10 text-gray-700 dark:text-white/50'"
                class="px-3 py-1 rounded-lg text-sm">
                URL
            </span>
        </label>
    </div>

    {{-- Upload File --}}
    <div x-show="!useUrl" class="space-y-2">
        <input type="file" name="featured_image_file" accept="image/*"
            class="focus:border-ring-brand-300 shadow-theme-xs focus:file:ring-brand-300 h-11 w-full overflow-hidden rounded-lg border border-gray-300 bg-transparent text-sm text-gray-500 transition-colors
                   file:mr-5 file:border-collapse file:cursor-pointer file:rounded-l-lg file:border-0 file:border-r file:border-solid file:border-gray-200 file:bg-gray-50 file:py-3 file:pr-3 file:pl-3.5 file:text-sm file:text-gray-700
                   placeholder:text-gray-400 hover:file:bg-gray-100 focus:outline-hidden
                   dark:border-gray-700 dark:bg-gray-900 dark:text-gray-400 dark:text-white/90
                   dark:file:border-gray-800 dark:file:bg-white/[0.03] dark:file:text-gray-400 dark:placeholder:text-gray-400" />
        @if(isset($article) && !str_starts_with($article->featured_image ?? '', 'http'))
        <img src="{{ asset('storage/' . $article->featured_image) }}" class="mt-2 w-40 h-24 object-cover rounded-lg">
        @endif
    </div>

    {{-- URL Input --}}
    <div x-show="useUrl">
        <input type="url" name="featured_image_url"
            value="{{ isset($article) && str_starts_with($article->featured_image ?? '', 'http') ? $article->featured_image : '' }}"
            placeholder="Masukkan URL gambar..."
            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 px-3 py-2 text-sm" />
        @if(isset($article) && str_starts_with($article->featured_image ?? '', 'http'))
        <img src="{{ $article->featured_image }}" class="mt-2 w-40 h-24 object-cover rounded-lg">
        @endif
    </div>

</div>