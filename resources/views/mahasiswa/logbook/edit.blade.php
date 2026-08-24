<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Edit Logbook - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Edit Logbook</x-slot:headerTitle>

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
                    Edit Logbook
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Perbaiki isi logbook sebelum dikirim kembali kepada mentor.
                </p>

            </div>

        </div>

        {{-- Informasi status --}}
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/40 dark:bg-amber-900/20">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                        Tanggal Logbook
                    </p>

                    <p class="mt-1 text-lg font-bold text-amber-900 dark:text-amber-200">
                        {{ $logbook->tanggal->translatedFormat('l, d F Y') }}
                    </p>

                    <p class="mt-1 text-xs text-amber-800 dark:text-amber-300">
                        Tanggal logbook tidak dapat diubah.
                    </p>

                </div>

                @if ($logbook->status === 'revision')

                <span class="inline-flex w-fit rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                    Perlu Revisi
                </span>

                @else

                <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    Draft
                </span>

                @endif

            </div>

        </div>

        {{-- Catatan Mentor --}}
        @if ($logbook->status === 'revision' && $logbook->catatan_mentor)

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 dark:border-rose-900/40 dark:bg-rose-900/20">

            <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-400">
                Catatan Mentor
            </p>

            <p class="mt-2 text-sm leading-6 text-rose-800 dark:text-rose-300">
                {{ $logbook->catatan_mentor }}
            </p>

        </div>

        @endif

        {{-- Validation Error --}}
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
            action="{{ route('mahasiswa.logbook.update', $logbook) }}"
            enctype="multipart/form-data"
            class="space-y-6">
            @csrf
            @method('PUT')

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
                            value="{{ old('judul_kegiatan', $logbook->judul_kegiatan) }}"
                            maxlength="150"
                            required
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
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('uraian_kegiatan', $logbook->uraian_kegiatan) }}</textarea>

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
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('hasil_kegiatan', $logbook->hasil_kegiatan) }}</textarea>

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
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('kendala', $logbook->kendala) }}</textarea>

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
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('rencana_tindak_lanjut', $logbook->rencana_tindak_lanjut) }}</textarea>

                    </div>

                    {{-- Bukti lama --}}
                    @if ($logbook->bukti_kegiatan)

                    <div>

                        <p class="mb-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Bukti Kegiatan Saat Ini
                        </p>

                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">

                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                File bukti sudah tersimpan.
                            </p>

                        </div>

                    </div>

                    @endif

                    {{-- Bukti baru --}}
                    <div>

                        <label
                            for="bukti_kegiatan"
                            class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Ganti Bukti Kegiatan
                        </label>

                        <input
                            type="file"
                            id="bukti_kegiatan"
                            name="bukti_kegiatan"
                            accept=".jpg,.jpeg,.png,.webp"
                            class="block w-full rounded-xl border border-gray-300 bg-white text-sm text-gray-700 file:mr-4 file:border-0 file:bg-emerald-50 file:px-4 file:py-3 file:text-sm file:font-semibold file:text-emerald-700 hover:file:bg-emerald-100 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300 dark:file:bg-emerald-900/30 dark:file:text-emerald-300">

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Kosongkan jika tidak ingin mengganti bukti. Maksimal 2 MB.
                        </p>

                    </div>

                </div>

            </div>

            {{-- Actions --}}
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

                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</x-simaga-layout>