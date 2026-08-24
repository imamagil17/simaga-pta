<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Detail Logbook - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Logbook</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- ============================================================
             FLASH MESSAGE
             ============================================================ --}}
        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">

            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </p>

        </div>

        @endif

        {{-- ============================================================
             ERROR
             ============================================================ --}}
        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">

            <ul class="space-y-1 text-sm text-rose-800 dark:text-rose-300">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        {{-- ============================================================
             BACK
             ============================================================ --}}
        <div>

            <a
                href="{{ route('mentor.logbook.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 dark:text-gray-400 dark:hover:text-emerald-400">

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>

                Kembali ke Logbook

            </a>

        </div>

        {{-- ============================================================
             MAHASISWA
             ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Mahasiswa
                    </p>

                    <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $logbook->penempatan->mahasiswa->user->name }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $logbook->penempatan->mahasiswa->nim }}
                    </p>

                </div>

                {{-- Status --}}
                @switch($logbook->status)

                @case('submitted')

                <span class="inline-flex w-fit rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                    Menunggu Pemeriksaan
                </span>

                @break

                @case('approved')

                <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                    Disetujui
                </span>

                @break

                @case('revision')

                <span class="inline-flex w-fit rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                    Perlu Revisi
                </span>

                @break

                @default

                <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    Draft
                </span>

                @endswitch

            </div>

        </div>

        {{-- ============================================================
             DETAIL LOGBOOK
             ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Tanggal --}}
            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Tanggal
                </p>

                <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                    {{ $logbook->tanggal->translatedFormat('l, d F Y') }}
                </p>

            </div>

            {{-- Judul --}}
            <div class="mt-6">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Judul Kegiatan
                </p>

                <h4 class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">
                    {{ $logbook->judul_kegiatan }}
                </h4>

            </div>

            {{-- Uraian --}}
            <div class="mt-6">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Uraian Kegiatan
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-gray-300">
                    {{ $logbook->uraian_kegiatan }}
                </p>

            </div>

            {{-- Hasil --}}
            @if ($logbook->hasil_kegiatan)

            <div class="mt-6">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Hasil Kegiatan
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-gray-300">
                    {{ $logbook->hasil_kegiatan }}
                </p>

            </div>

            @endif

            {{-- Kendala --}}
            @if ($logbook->kendala)

            <div class="mt-6">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Kendala
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-gray-300">
                    {{ $logbook->kendala }}
                </p>

            </div>

            @endif

            {{-- Rencana --}}
            @if ($logbook->rencana_tindak_lanjut)

            <div class="mt-6">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Rencana Tindak Lanjut
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-gray-300">
                    {{ $logbook->rencana_tindak_lanjut }}
                </p>

            </div>

            @endif

            {{-- ========================================================
                 BUKTI KEGIATAN
                 ======================================================== --}}
            @if ($logbook->bukti_kegiatan)

            <div class="mt-6">

                <div class="flex items-center justify-between gap-3">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Bukti Kegiatan
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Dokumentasi kegiatan yang diunggah mahasiswa.
                        </p>

                    </div>

                    <a
                        href="{{ Storage::url($logbook->bukti_kegiatan) }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4m-5-10h7m0 0v7m0-7L10 14" />
                        </svg>

                        Buka Gambar

                    </a>

                </div>

                <div class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/40">

                    <img
                        src="{{ Storage::url($logbook->bukti_kegiatan) }}"
                        alt="Bukti kegiatan {{ $logbook->judul_kegiatan }}"
                        class="mx-auto max-h-[600px] w-auto max-w-full object-contain"
                        loading="lazy">

                </div>

            </div>

            @else

            <div class="mt-6 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">

                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                    Tidak ada bukti kegiatan.
                </p>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Mahasiswa tidak mengunggah dokumentasi untuk kegiatan ini.
                </p>

            </div>

            @endif

        </div>

        {{-- ============================================================
             CATATAN MENTOR
             ============================================================ --}}
        @if ($logbook->catatan_mentor)

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6 dark:border-rose-900/40 dark:bg-rose-900/20">

            <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-400">
                Catatan Mentor
            </p>

            <p class="mt-2 whitespace-pre-line text-sm leading-7 text-rose-800 dark:text-rose-300">
                {{ $logbook->catatan_mentor }}
            </p>

            @if ($logbook->reviewed_at)

            <p class="mt-3 text-xs text-rose-600/80 dark:text-rose-400/80">
                Diperiksa pada
                {{ $logbook->reviewed_at->translatedFormat('d F Y, H:i') }}
                WITA
            </p>

            @endif

        </div>

        @endif

        {{-- ============================================================
             AKSI MENTOR
             ============================================================ --}}
        @if ($logbook->status === 'submitted')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- ACC --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <h3 class="font-bold text-emerald-900 dark:text-emerald-200">
                    Setujui Logbook
                </h3>

                <p class="mt-1 text-sm text-emerald-800 dark:text-emerald-300">
                    Jika kegiatan sudah sesuai, Anda dapat menyetujui logbook ini.
                </p>

                <form
                    method="POST"
                    action="{{ route('mentor.logbook.approve', $logbook) }}"
                    class="mt-5">
                    @csrf

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
                        ✓ Setujui Logbook
                    </button>

                </form>

            </div>

            {{-- REVISI --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6 dark:border-rose-900/40 dark:bg-rose-900/20">

                <h3 class="font-bold text-rose-900 dark:text-rose-200">
                    Minta Revisi
                </h3>

                <p class="mt-1 text-sm text-rose-800 dark:text-rose-300">
                    Jelaskan bagian yang perlu diperbaiki mahasiswa.
                </p>

                <form
                    method="POST"
                    action="{{ route('mentor.logbook.revision', $logbook) }}"
                    class="mt-5 space-y-4">
                    @csrf

                    <textarea
                        name="catatan_mentor"
                        rows="4"
                        required
                        minlength="5"
                        placeholder="Contoh: Uraian kegiatan mohon dibuat lebih detail..."
                        class="block w-full rounded-xl border-rose-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm focus:border-rose-500 focus:ring-rose-500 dark:border-rose-900/50 dark:bg-gray-900 dark:text-gray-100">{{ old('catatan_mentor') }}</textarea>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Minta Revisi
                    </button>

                </form>

            </div>

        </div>

        @endif

    </div>

</x-simaga-layout>