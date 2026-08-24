<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Detail Logbook - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Logbook</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Back --}}
        <div>

            <a
                href="{{ route('admin.logbook.index') }}"
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

                Kembali ke Monitoring
            </a>

        </div>

        {{-- ============================================================
             IDENTITAS
             ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

                {{-- Mahasiswa --}}
                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Mahasiswa
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">
                        {{ $logbook->penempatan->mahasiswa->user->name ?? '-' }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $logbook->penempatan->mahasiswa->nim ?? '-' }}
                    </p>

                </div>

                {{-- Mentor --}}
                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Mentor
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $logbook->penempatan->mentor->user->name ?? '-' }}
                    </p>

                </div>

                {{-- Periode --}}
                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Periode
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $logbook->penempatan->periodeMagang->nama_periode ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- ============================================================
             STATUS
             ============================================================ --}}
        <div class="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Status Logbook
                </p>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Dicatat pada {{ $logbook->tanggal->translatedFormat('l, d F Y') }}
                </p>

            </div>

            @switch($logbook->status)

            @case('draft')

            <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                Draft
            </span>

            @break

            @case('submitted')

            <span class="inline-flex w-fit rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                Menunggu Mentor
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

            @endswitch

        </div>

        {{-- ============================================================
             DETAIL KEGIATAN
             ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Judul Kegiatan
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $logbook->judul_kegiatan }}
                </h3>

            </div>

            <div class="mt-6">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Uraian Kegiatan
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-gray-300">
                    {{ $logbook->uraian_kegiatan }}
                </p>

            </div>

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

        </div>

        {{-- ============================================================
             BUKTI KEGIATAN
             ============================================================ --}}
        @if ($logbook->bukti_kegiatan)

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Bukti Kegiatan
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Dokumentasi yang diunggah mahasiswa.
                    </p>

                </div>

                <a
                    href="{{ asset('storage/' . $logbook->bukti_kegiatan) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Buka Gambar
                </a>

            </div>

            <div class="mt-5 overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 dark:border-gray-700 dark:bg-gray-900/40">

                <img
                    src="{{ asset('storage/' . $logbook->bukti_kegiatan) }}"
                    alt="Bukti {{ $logbook->judul_kegiatan }}"
                    class="mx-auto max-h-[650px] w-auto max-w-full object-contain"
                    loading="lazy">

            </div>

        </div>

        @else

        <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-900/40">

            <p class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                Tidak ada bukti kegiatan.
            </p>

        </div>

        @endif

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
             INFORMASI REVIEW
             ============================================================ --}}
        @if ($logbook->reviewer)

        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6 dark:border-gray-700 dark:bg-gray-900/40">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                Pemeriksaan
            </p>

            <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                Diperiksa oleh
                <strong>
                    {{ $logbook->reviewer->name }}
                </strong>
                @if ($logbook->reviewed_at)
                pada {{ $logbook->reviewed_at->translatedFormat('d F Y, H:i') }} WITA
                @endif
            </p>

        </div>

        @endif

    </div>

</x-simaga-layout>