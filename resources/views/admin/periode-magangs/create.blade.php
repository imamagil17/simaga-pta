<x-simaga-layout>
    <x-slot:title>Tambah Periode Magang - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Tambah Periode Magang</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <a
                href="{{ route('admin.periode-magangs.index') }}"
                class="mb-3 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:underline dark:text-emerald-400"
            >
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"
                    />
                </svg>

                Kembali ke Periode Magang
            </a>

            <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                Tambah Periode Magang
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Tambahkan periode pelaksanaan magang baru ke dalam SIMAGA PTA.
            </p>
        </div>

        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('admin.periode-magangs.store') }}"
            class="space-y-6"
        >
            @csrf

            {{-- General Error --}}
            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">
                    <div class="text-sm font-semibold text-rose-800 dark:text-rose-200">
                        Periksa kembali data yang dimasukkan.
                    </div>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-700 dark:text-rose-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Informasi Periode --}}
            <div class="space-y-6 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Informasi Periode
                    </h4>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Tentukan identitas dan waktu pelaksanaan periode magang.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- Nama --}}
                    <div class="md:col-span-2">
                        <label for="nama_periode" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nama Periode
                        </label>

                        <input
                            type="text"
                            id="nama_periode"
                            name="nama_periode"
                            value="{{ old('nama_periode') }}"
                            required
                            placeholder="Contoh: Magang Semester Ganjil 2026"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('nama_periode')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Kode --}}
                    <div>
                        <label for="kode_periode" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Kode Periode
                        </label>

                        <input
                            type="text"
                            id="kode_periode"
                            name="kode_periode"
                            value="{{ old('kode_periode') }}"
                            required
                            placeholder="Contoh: MAG-2026-GANJIL"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 font-mono text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('kode_periode')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="active" @selected(old('status', 'active') === 'active')}>
                                Aktif
                            </option>

                            <option value="inactive" @selected(old('status') === 'inactive')>
                                Tidak Aktif
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Mulai --}}
                    <div>
                        <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            id="tanggal_mulai"
                            name="tanggal_mulai"
                            value="{{ old('tanggal_mulai') }}"
                            required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('tanggal_mulai')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Selesai --}}
                    <div>
                        <label for="tanggal_selesai" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            id="tanggal_selesai"
                            name="tanggal_selesai"
                            value="{{ old('tanggal_selesai') }}"
                            required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('tanggal_selesai')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Keterangan --}}
                    <div class="md:col-span-2">
                        <label for="keterangan" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Keterangan
                        </label>

                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="4"
                            placeholder="Tambahkan keterangan jika diperlukan..."
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
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
                    href="{{ route('admin.periode-magangs.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800"
                >
                    Simpan Periode
                </button>

            </div>

        </form>

    </div>
</x-simaga-layout>