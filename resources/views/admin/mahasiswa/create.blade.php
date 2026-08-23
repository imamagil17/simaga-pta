<x-simaga-layout>
    <x-slot:title>Lengkapi Profil Mahasiswa - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Lengkapi Profil Mahasiswa</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex items-start gap-4">

                <a
                    href="{{ route('admin.mahasiswa.index') }}"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-300 bg-white text-gray-600 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    title="Kembali"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </a>

                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            Lengkapi Profil Mahasiswa
                        </h3>

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-semibold text-amber-800 dark:bg-amber-900/30 dark:text-amber-300">
                            Profil Belum Lengkap
                        </span>
                    </div>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Lengkapi informasi akademik dan pribadi mahasiswa.
                    </p>
                </div>

            </div>

        </div>

        <form
            method="POST"
            action="{{ route('admin.mahasiswa.profile.store', $user) }}"
            enctype="multipart/form-data"
            class="space-y-6"
        >
            @csrf

            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">
                    <p class="text-sm font-semibold text-rose-800 dark:text-rose-200">
                        Periksa kembali data yang dimasukkan.
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-700 dark:text-rose-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Informasi Akun --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Informasi Akun
                    </h4>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Lengkap
                        </label>

                        <input
                            type="text"
                            value="{{ $user->name }}"
                            disabled
                            class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-3 text-sm text-gray-600 dark:border-gray-600 dark:bg-gray-900/60 dark:text-gray-400"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email
                        </label>

                        <input
                            type="email"
                            value="{{ $user->email }}"
                            disabled
                            class="w-full rounded-xl border-gray-300 bg-gray-100 px-4 py-3 text-sm text-gray-600 dark:border-gray-600 dark:bg-gray-900/60 dark:text-gray-400"
                        >
                    </div>

                </div>

            </div>

            {{-- Data Akademik --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Data Akademik
                    </h4>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi akademik mahasiswa.
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label for="nim" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            NIM
                        </label>

                        <input
                            type="text"
                            id="nim"
                            name="nim"
                            value="{{ old('nim') }}"
                            required
                            placeholder="Nomor Induk Mahasiswa"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('nim')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="perguruan_tinggi" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Perguruan Tinggi
                        </label>

                        <input
                            type="text"
                            id="perguruan_tinggi"
                            name="perguruan_tinggi"
                            value="{{ old('perguruan_tinggi') }}"
                            required
                            placeholder="Nama universitas / perguruan tinggi"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('perguruan_tinggi')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="program_studi" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Program Studi
                        </label>

                        <input
                            type="text"
                            id="program_studi"
                            name="program_studi"
                            value="{{ old('program_studi') }}"
                            required
                            placeholder="Contoh: Teknik Informatika"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('program_studi')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- Informasi Pribadi --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Informasi Pribadi
                    </h4>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label for="jenis_kelamin" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Jenis Kelamin
                        </label>

                        <select
                            id="jenis_kelamin"
                            name="jenis_kelamin"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" @selected(old('jenis_kelamin') === 'Laki-laki')}>
                                Laki-laki
                            </option>
                            <option value="Perempuan" @selected(old('jenis_kelamin') === 'Perempuan')}>
                                Perempuan
                            </option>
                        </select>

                        @error('jenis_kelamin')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="agama" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Agama
                        </label>

                        <select
                            id="agama"
                            name="agama"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Pilih Agama</option>

                            @foreach([
                                'Islam',
                                'Kristen Protestan',
                                'Kristen Katolik',
                                'Hindu',
                                'Buddha',
                                'Konghucu'
                            ] as $agama)

                                <option
                                    value="{{ $agama }}"
                                    @selected(old('agama') === $agama)
                                >
                                    {{ $agama }}
                                </option>

                            @endforeach

                        </select>

                        @error('agama')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="no_hp" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            No. HP
                        </label>

                        <input
                            type="text"
                            id="no_hp"
                            name="no_hp"
                            value="{{ old('no_hp') }}"
                            placeholder="08xxxxxxxxxx"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('no_hp')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="foto" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Foto
                        </label>

                        <input
                            type="file"
                            id="foto"
                            name="foto"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full rounded-xl border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-emerald-50 file:px-4 file:py-3 file:font-medium file:text-emerald-700 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:file:bg-emerald-900/30 dark:file:text-emerald-300"
                        >

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            JPG, PNG, atau WebP. Maksimal 2 MB.
                        </p>

                        @error('foto')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label for="alamat" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Alamat
                        </label>

                        <textarea
                            id="alamat"
                            name="alamat"
                            rows="3"
                            placeholder="Alamat tempat tinggal mahasiswa"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >{{ old('alamat') }}</textarea>

                        @error('alamat')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- Status --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Status & Keterangan
                    </h4>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status Profil
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="active" @selected(old('status', 'active') === 'active')}>
                                Aktif
                            </option>

                            <option value="inactive" @selected(old('status') === 'inactive')}>
                                Tidak Aktif
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Keterangan
                        </label>

                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="3"
                            placeholder="Keterangan tambahan jika diperlukan"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >{{ old('keterangan') }}</textarea>

                        @error('keterangan')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- Actions --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('admin.mahasiswa.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                    Simpan Profil
                </button>

            </div>

        </form>

    </div>
</x-simaga-layout>