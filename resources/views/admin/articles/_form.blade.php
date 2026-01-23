<x-common.component-card>
    @slot('title')
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ $title ?? 'Form Artikel' }}
    </h3>
    @endslot

    <div class="space-y-6">

        {{-- JUDUL --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Judul</label>
            <input type="text"
                name="judul"
                value="{{ old('judul', $article->judul ?? '') }}"
                required
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                placeholder="Judul artikel...">
        </div>

        {{-- KATEGORI --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Kategori</label>
            <input type="text"
                name="kategori"
                value="{{ old('kategori', $article->kategori ?? '') }}"
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                placeholder="Kategori artikel...">
        </div>

        {{-- AUTHOR --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Author</label>
            <input type="text"
                name="author"
                value="{{ old('author', $article->author ?? '') }}"
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                placeholder="Nama penulis...">
        </div>

        {{-- TANGGAL POST --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Tanggal Post</label>
            <input type="text"
                x-data
                x-init="flatpickr($el, { dateFormat: 'd-m-Y', defaultDate: '{{ old('tanggal_post', isset($article->tanggal_post) ? $article->tanggal_post->format('d-m-Y') : now()->format('d-m-Y')) }}' })"
                name="tanggal_post"
                required
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">
        </div>

        {{-- WAKTU BACA --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Waktu Baca</label>
            <input type="text"
                name="waktu_baca"
                value="{{ old('waktu_baca', $article->waktu_baca ?? '') }}"
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                placeholder="Contoh: 5 menit">
        </div>

        {{-- FEATURED IMAGE (Upload atau URL) --}}
        @include('admin.articles._form-image')

        {{-- KONTEN --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Konten</label>
            <textarea name="konten" rows="8"
                required
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90"
                placeholder="Tulis konten artikel...">{{ old('konten', $article->konten ?? '') }}</textarea>
        </div>

        {{-- TAGS --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">Tags</label>
            <input type="text"
                name="tags[]"
                value="{{ old('tags.0', isset($article->tags[0]) ? $article->tags[0] : '') }}"
                placeholder="Pisahkan tags dengan koma"
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800 focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10 dark:border-gray-700 dark:text-white/90">
        </div>

    </div>
</x-common.component-card>