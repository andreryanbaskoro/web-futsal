<x-common.component-card>
    @slot('title')
    <h3 class="text-xl font-semibold text-gray-800 dark:text-white/90">
        {{ $title }}
    </h3>
    @endslot

    <div class="space-y-6">

        {{-- NAMA --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Nama
            </label>
            <input
                type="text"
                name="nama"
                value="{{ old('nama', $pengguna->nama ?? '') }}"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800
                       placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                       dark:placeholder:text-white/30"
                placeholder="Nama pengguna" />
        </div>

        {{-- EMAIL --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Email
            </label>
            <input
                type="email"
                name="email"
                value="{{ old('email', $pengguna->email ?? '') }}"
                required
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800
                       placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                       dark:placeholder:text-white/30"
                placeholder="Email pengguna" />
        </div>

        {{-- PASSWORD --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Password
            </label>
            <input
                type="password"
                name="password"
                @if(!isset($pengguna)) required @endif
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800
                       placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                       dark:placeholder:text-white/30"
                placeholder="{{ isset($pengguna) ? 'Kosongkan jika tidak diubah' : 'Password pengguna' }}" />
        </div>

        {{-- NO HP --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                No HP
            </label>
            <input
                type="text"
                name="no_hp"
                value="{{ old('no_hp', $pengguna->no_hp ?? '') }}"
                class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10
                       dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300
                       bg-transparent px-4 py-2.5 text-sm text-gray-800
                       placeholder:text-gray-400 focus:ring-3 focus:outline-hidden
                       dark:border-gray-700 dark:bg-gray-900 dark:text-white/90
                       dark:placeholder:text-white/30"
                placeholder="Nomor HP" />
        </div>

        {{-- PERAN --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Peran
            </label>
            <select name="peran" class="w-full rounded-lg border px-4 py-2 dark:bg-dark-900">
                @php
                $roles = ['admin' => 'Admin', 'owner' => 'Owner', 'pelanggan' => 'Pelanggan'];
                $selected = old('peran', $pengguna->peran ?? 'pelanggan');
                @endphp
                @foreach($roles as $key => $label)
                <option value="{{ $key }}" @selected($selected===$key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

        {{-- STATUS --}}
        <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                Status
            </label>
            <select name="status" class="w-full rounded-lg border px-4 py-2 dark:bg-dark-900">
                @php
                $statuses = ['active' => 'Active', 'inactive' => 'Inactive', 'suspended' => 'Suspended'];
                $selectedStatus = old('status', $pengguna->status ?? 'active');
                @endphp
                @foreach($statuses as $key => $label)
                <option value="{{ $key }}" @selected($selectedStatus===$key)>{{ $label }}</option>
                @endforeach
            </select>
        </div>

          </div>
</x-common.component-card>