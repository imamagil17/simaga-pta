<x-simaga-layout>
    <x-slot:title>Edit Profil Mentor - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Edit Profil Mentor</x-slot:headerTitle>

    <div class="max-w-5xl mx-auto space-y-6">

        {{-- Top Header --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a
                    href="{{ route('admin.mentors.index') }}"
                    class="mb-2 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:underline dark:text-emerald-400"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>

                    Kembali ke Daftar Mentor
                </a>

                <h3 class="flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                    <svg class="h-6 w-6 text-emerald-700 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                    </svg>

                    Edit Profil Mentor
                </h3>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                    Perbarui data profil dan kepegawaian mentor yang terdaftar.
                </p>
            </div>

            <div>
                <span
                    class="{{ $mentor->status === 'active'
                        ? 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-700/50 dark:bg-emerald-900/30 dark:text-emerald-300'
                        : 'border-gray-200 bg-gray-100 text-gray-700 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300' }}
                    inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs font-semibold"
                >
                    <span
                        class="{{ $mentor->status === 'active'
                            ? 'bg-emerald-500'
                            : 'bg-gray-400' }}
                            h-2 w-2 rounded-full"
                    ></span>

                    {{ $mentor->status === 'active' ? 'Profil Aktif' : 'Profil Tidak Aktif' }}
                </span>
            </div>
        </div>

        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('admin.mentors.profile.update', $user) }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf
            @method('PUT')

            {{-- Error Global --}}
            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">
                    <div class="flex gap-3">
                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8v4m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                                />
                            </svg>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-rose-800 dark:text-rose-200">
                                Periksa kembali data
                            </h4>

                            <ul class="mt-1 list-disc space-y-1 pl-5 text-sm text-rose-700 dark:text-rose-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Bagian 1 --}}
            <div class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 pb-3 dark:border-gray-700">
                    <h4 class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-gray-100">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
                        Bagian 1: Profil Akun
                    </h4>

                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        Informasi akun berasal dari pengguna dan tidak diedit pada halaman ini.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 rounded-xl border border-gray-200/80 bg-gray-50 p-4 dark:border-gray-700/80 dark:bg-gray-700/40 md:grid-cols-3">

                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">
                            Nama Lengkap
                        </span>

                        <span class="mt-1 block text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $user->name }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">
                            Alamat Email
                        </span>

                        <span class="mt-1 block text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $user->email }}
                        </span>
                    </div>

                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">
                            Role Sistem
                        </span>

                        <span class="mt-1 inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-0.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                            Mentor
                        </span>
                    </div>

                </div>
            </div>

            {{-- Bagian 2 --}}
            <div class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 pb-3 dark:border-gray-700">
                    <h4 class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-gray-100">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
                        Bagian 2: Data Kepegawaian
                    </h4>

                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        Perbarui informasi nomor induk dan unit kerja mentor.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">

                    {{-- NIP --}}
                    <div>
                        <label
                            for="nip"
                            class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                        >
                            NIP
                        </label>

                        <input
                            type="text"
                            name="nip"
                            id="nip"
                            value="{{ old('nip', $mentor->nip) }}"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="Masukkan NIP"
                        >

                        @error('nip')
                            <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Jabatan --}}
                    <div>
                        <label
                            for="jabatan"
                            class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                        >
                            Jabatan
                        </label>

                        <input
                            type="text"
                            name="jabatan"
                            id="jabatan"
                            value="{{ old('jabatan', $mentor->jabatan) }}"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="Masukkan jabatan mentor"
                        >

                        @error('jabatan')
                            <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Bagian --}}
                    <div>
                        <label
                            for="bagian"
                            class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                        >
                            Bagian
                        </label>

                        <input
                            type="text"
                            name="bagian"
                            id="bagian"
                            value="{{ old('bagian', $mentor->bagian) }}"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="Masukkan bagian/unit kerja"
                        >

                        @error('bagian')
                            <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Bagian 3 --}}
            <div class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 pb-3">
                    <h4 class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-gray-100">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
                        Bagian 3: Informasi Pribadi
                    </h4>

                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        Perbarui data pribadi dan kontak mentor.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Jenis Kelamin --}}
                    <div>
                        <label
                            for="jenis_kelamin"
                            class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                        >
                            Jenis Kelamin
                        </label>

                        <select
                            name="jenis_kelamin"
                            id="jenis_kelamin"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Pilih Jenis Kelamin</option>

                            <option
                                value="Laki-laki"
                                @selected(old('jenis_kelamin', $mentor->jenis_kelamin) === 'Laki-laki')
                            >
                                Laki-laki
                            </option>

                            <option
                                value="Perempuan"
                                @selected(old('jenis_kelamin', $mentor->jenis_kelamin) === 'Perempuan')
                            >
                                Perempuan
                            </option>
                        </select>

                        @error('jenis_kelamin')
                            <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Agama --}}
                    <div>
                        <label
                            for="agama"
                            class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                        >
                            Agama
                        </label>

                        <select
                            name="agama"
                            id="agama"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Pilih Agama</option>

                            @foreach ([
                                'Islam',
                                'Kristen Protestan',
                                'Kristen Katolik',
                                'Hindu',
                                'Buddha',
                                'Konghucu'
                            ] as $agamaOption)

                                <option
                                    value="{{ $agamaOption }}"
                                    @selected(old('agama', $mentor->agama) === $agamaOption)
                                >
                                    {{ $agamaOption }}
                                </option>

                            @endforeach
                        </select>

                        @error('agama')
                            <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- No HP --}}
                    <div>
                        <label
                            for="no_hp"
                            class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                        >
                            No. HP
                        </label>

                        <input
                            type="tel"
                            name="no_hp"
                            id="no_hp"
                            value="{{ old('no_hp', $mentor->no_hp) }}"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="Contoh: 08xxxxxxxxxx"
                        >

                        @error('no_hp')
                            <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Foto --}}
                    <div>
                        <label
                            for="foto"
                            class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                        >
                            Foto Profil
                        </label>

                        @if ($mentor->foto)
                            <div class="mb-3 rounded-xl border border-gray-200 bg-gray-50 p-3 dark:border-gray-700 dark:bg-gray-900/50">
                                <p class="text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                    Foto saat ini
                                </p>

                                <p class="mt-1 truncate text-xs text-gray-700 dark:text-gray-300">
                                    {{ basename($mentor->foto) }}
                                </p>
                            </div>
                        @endif

                        <input
                            type="file"
                            name="foto"
                            id="foto"
                            accept="image/jpeg,image/png,image/webp"
                            class="w-full rounded-lg border border-gray-300 bg-white text-xs text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-emerald-50 file:px-4 file:py-2 file:text-xs file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-400 dark:file:bg-emerald-900/50 dark:file:text-emerald-300"
                        >

                        <span class="mt-1 block text-[11px] text-gray-400 dark:text-gray-500">
                            Kosongkan jika tidak ingin mengganti foto. Format JPG, PNG, atau WebP. Maksimal 2 MB.
                        </span>

                        @error('foto')
                            <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Bagian 4 --}}
            <div class="space-y-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <div class="border-b border-gray-200 pb-3">
                    <h4 class="flex items-center gap-2 text-base font-bold text-gray-900 dark:text-gray-100">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-600"></span>
                        Bagian 4: Status & Keterangan
                    </h4>

                    <p class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                        Perbarui status profil dan catatan tambahan.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Status --}}
                    <div>
                        <label
                            for="status"
                            class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                        >
                            Status Profil <span class="text-rose-500">*</span>
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option
                                value="active"
                                @selected(old('status', $mentor->status) === 'active')
                            >
                                Aktif
                            </option>

                            <option
                                value="inactive"
                                @selected(old('status', $mentor->status) === 'inactive')
                            >
                                Tidak Aktif
                            </option>
                        </select>

                        @error('status')
                            <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="md:col-span-2">
                        <label
                            for="keterangan"
                            class="mb-1 block text-xs font-semibold text-gray-700 dark:text-gray-300"
                        >
                            Keterangan
                        </label>

                        <textarea
                            name="keterangan"
                            id="keterangan"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 bg-white text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="Tambahkan keterangan jika diperlukan..."
                        >{{ old('keterangan', $mentor->keterangan) }}</textarea>

                        @error('keterangan')
                            <span class="mt-1 block text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex items-center justify-end gap-3 rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <a
                    href="{{ route('admin.mentors.index') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-lg bg-emerald-700 px-5 py-2.5 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>
</x-simaga-layout>