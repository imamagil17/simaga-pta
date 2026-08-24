<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Tambah Logbook - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Tambah Logbook</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div class="flex items-start gap-3">

            <a
                href="{{ route('mahasiswa.logbook.index') }}"
                class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                <svg
                    class="h-5 w-5"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>
            </a>

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Aktivitas Magang
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Buat Logbook
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Catat kegiatan magang yang Anda lakukan hari ini.
                </p>

            </div>

        </div>

        {{-- Informasi tanggal --}}
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">

            <div class="flex items-center gap-4">

                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">

                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3M5 11h14M5 5h14a2 2 0 012 2v13a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2z" />
                    </svg>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                        Tanggal Kegiatan
                    </p>

                    <p class="mt-1 text-lg font-bold text-emerald-900 dark:text-emerald-200">
                        {{ $tanggal->translatedFormat('l, d F Y') }}
                    </p>

                    <p class="mt-1 text-xs text-emerald-700 dark:text-emerald-400">
                        Tanggal ditentukan otomatis berdasarkan hari ini.
                    </p>

                </div>

            </div>

        </div>

        {{-- Error --}}
        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 dark:border-rose-900/40 dark:bg-rose-900/20">

            <p class="font-semibold text-rose-900 dark:text-rose-200">
                Periksa kembali data Anda.
            </p>

            <ul class="mt-2 space-y-1 text-sm text-rose-800 dark:text-rose-300">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>

        </div>

        @endif

        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('mahasiswa.logbook.store') }}"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="space-y-6">

                    {{-- Judul --}}
                    <div>

                        <label
                            for="judul_kegiatan"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Judul Kegiatan
                            <span class="text-rose-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="judul_kegiatan"
                            name="judul_kegiatan"
                            value="{{ old('judul_kegiatan') }}"
                            maxlength="150"
                            required
                            placeholder="Contoh: Pengembangan Modul Absensi"
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                    </div>

                    {{-- Uraian --}}
                    <div>

                        <label
                            for="uraian_kegiatan"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Uraian Kegiatan
                            <span class="text-rose-500">*</span>
                        </label>

                        <textarea
                            id="uraian_kegiatan"
                            name="uraian_kegiatan"
                            rows="5"
                            required
                            placeholder="Jelaskan kegiatan yang Anda lakukan hari ini..."
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('uraian_kegiatan') }}</textarea>

                    </div>

                    {{-- Hasil --}}
                    <div>

                        <label
                            for="hasil_kegiatan"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Hasil Kegiatan
                        </label>

                        <textarea
                            id="hasil_kegiatan"
                            name="hasil_kegiatan"
                            rows="4"
                            placeholder="Tuliskan hasil yang diperoleh dari kegiatan..."
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('hasil_kegiatan') }}</textarea>

                    </div>

                    {{-- Kendala --}}
                    <div>

                        <label
                            for="kendala"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Kendala
                        </label>

                        <textarea
                            id="kendala"
                            name="kendala"
                            rows="3"
                            placeholder="Tuliskan kendala yang ditemui, jika ada..."
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('kendala') }}</textarea>

                    </div>

                    {{-- Rencana --}}
                    <div>

                        <label
                            for="rencana_tindak_lanjut"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Rencana Tindak Lanjut
                        </label>

                        <textarea
                            id="rencana_tindak_lanjut"
                            name="rencana_tindak_lanjut"
                            rows="3"
                            placeholder="Tuliskan rencana kegiatan berikutnya, jika ada..."
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('rencana_tindak_lanjut') }}</textarea>

                    </div>

                    {{-- Bukti --}}
                    <div>

                        <label
                            for="bukti_kegiatan"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Bukti Kegiatan
                        </label>

                        <input
                            type="file"
                            id="bukti_kegiatan"
                            name="bukti_kegiatan"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full rounded-xl border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-emerald-50 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:file:bg-emerald-900/30 dark:file:text-emerald-300">

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                        </p>

                    </div>

                </div>

            </div>

            {{-- Tombol --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('mahasiswa.logbook.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7" />
                    </svg>

                    Simpan Logbook
                </button>

            </div>

        </form>

    </div>

</x-simaga-layout>