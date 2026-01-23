<x-common.component-card>
    @slot('title')
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ $title ?? 'Form Galeri' }}
    </h3>
    @endslot

    <div class="space-y-6">

        {{-- JUDUL --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                Judul
            </label>
            <input type="text"
                name="title"
                value="{{ old('title', $gallery->title ?? '') }}"
                required
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800
                       focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10
                       dark:border-gray-700 dark:text-white/90"
                placeholder="Judul gambar...">
        </div>

        {{-- KATEGORI --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                Kategori
            </label>
            <select name="category"
                required
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800
                       focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10
                       dark:border-gray-700 dark:text-white/90">
                <option value="">-- Pilih Kategori --</option>
                @php
                $categories = ['lapangan', 'fasilitas', 'aktivitas', 'event'];
                @endphp
                @foreach($categories as $cat)
                <option value="{{ $cat }}"
                    {{ old('category', $gallery->category ?? '') === $cat ? 'selected' : '' }}>
                    {{ ucfirst($cat) }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- DESKRIPSI --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-400">
                Deskripsi
            </label>
            <textarea name="description"
                rows="4"
                class="dark:bg-dark-900 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm text-gray-800
                       focus:ring-3 focus:outline-none focus:border-brand-300 focus:ring-brand-500/10
                       dark:border-gray-700 dark:text-white/90"
                placeholder="Deskripsi singkat (opsional)...">{{ old('description', $gallery->description ?? '') }}</textarea>
        </div>

        {{-- IMAGE (Upload / URL) --}}
        @include('admin.galleries._form-image')

        {{-- STATUS AKTIF (TOGGLE) --}}
        @php
        $isActive = old('is_active', $gallery->is_active ?? true);
        @endphp

        <div x-data="{ switcherToggle: {{ $isActive ? 'true' : 'false' }} }">
            <label
                class="flex cursor-pointer items-center gap-3 text-sm font-medium
               text-gray-700 select-none dark:text-gray-400">

                <div class="relative">
                    {{-- Checkbox visual --}}
                    <input
                        type="checkbox"
                        class="sr-only"
                        :checked="switcherToggle"
                        @change="switcherToggle = !switcherToggle" />

                    {{-- VALUE YANG DIKIRIM KE BACKEND --}}
                    <input
                        type="hidden"
                        name="is_active"
                        :value="switcherToggle ? 1 : 0">

                    {{-- TRACK --}}
                    <div
                        class="block h-6 w-11 rounded-full transition"
                        :class="switcherToggle
                    ? 'bg-brand-500 dark:bg-brand-500'
                    : 'bg-gray-200 dark:bg-white/10'">
                    </div>

                    {{-- THUMB --}}
                    <div
                        class="shadow-theme-sm absolute top-0.5 left-0.5 h-5 w-5
                       rounded-full bg-white duration-300 ease-linear"
                        :class="switcherToggle ? 'translate-x-full' : 'translate-x-0'">
                    </div>
                </div>

                <span x-text="switcherToggle ? 'Aktif' : 'Nonaktif'"></span>
            </label>
        </div>


    </div>
</x-common.component-card>