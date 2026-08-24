<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Logbook - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Logbook</x-slot:headerTitle>

    <div class="mx-auto max-w-6xl space-y-6">

        {{-- ============================================================
        FLASH MESSAGE
        ============================================================ --}}
        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">

            <div class="flex items-center gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    ✓
                </div>

                <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                    {{ session('success') }}
                </p>

            </div>

        </div>

        @endif

        @if ($errors->has('logbook'))

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">

            <div class="flex items-start gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-rose-100 text-rose-700 dark:bg-rose-900/40 dark:text-rose-300">
                    !
                </div>

                <div>

                    <p class="text-sm font-semibold text-rose-800 dark:text-rose-300">
                        {{ $errors->first('logbook') }}
                    </p>

                </div>

            </div>

        </div>

        @endif

        {{-- ============================================================
        HEADER
        ============================================================ --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Aktivitas Magang
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Logbook Kegiatan
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Catat kegiatan magang Anda setiap hari kerja.
                </p>

            </div>

            <a
                href="{{ route('mahasiswa.dashboard') }}"
                class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
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

                Dashboard
            </a>

        </div>

        @if ($penempatan)

        {{-- ========================================================
        INFORMASI MAGANG
        ======================================================== --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Periode Magang
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->periodeMagang->nama_periode }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ \Carbon\Carbon::parse($penempatan->periodeMagang->tanggal_mulai)->translatedFormat('d F Y') }}
                        -
                        {{ \Carbon\Carbon::parse($penempatan->periodeMagang->tanggal_selesai)->translatedFormat('d F Y') }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Mentor
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->mentor->user->name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Status Periode
                    </p>

                    @if ($periodeBerjalan)

                    <span class="mt-1 inline-flex items-center rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                        Sedang Berjalan
                    </span>

                    @else

                    <span class="mt-1 inline-flex items-center rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                        Tidak Aktif
                    </span>

                    @endif

                </div>

            </div>

        </div>

        {{-- ========================================================
                 LOGBOOK HARI INI
                 ======================================================== --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                        Logbook Hari Ini
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </h3>

                </div>

                @if ($logbookHariIni)

                @switch($logbookHariIni->status)

                @case('draft')

                <span class="inline-flex w-fit items-center rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    Draft
                </span>

                @break

                @case('submitted')

                <span class="inline-flex w-fit items-center rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                    Menunggu Mentor
                </span>

                @break

                @case('approved')

                <span class="inline-flex w-fit items-center rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                    Disetujui
                </span>

                @break

                @case('revision')

                <span class="inline-flex w-fit items-center rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                    Perlu Revisi
                </span>

                @break

                @endswitch

                @endif

            </div>

            {{-- ====================================================
                     HARI LIBUR / PERIODE TIDAK AKTIF
                     ==================================================== --}}

            @if (!$hariKerja)

            <div class="mt-5 rounded-xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-900/40 dark:bg-blue-900/20">

                <div class="flex gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">

                        <svg
                            class="h-5 w-5"
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

                        <p class="font-semibold text-blue-900 dark:text-blue-200">
                            Hari Libur
                        </p>

                        <p class="mt-1 text-sm text-blue-800 dark:text-blue-300">
                            Logbook hanya dapat dibuat pada hari kerja, yaitu Senin sampai Jumat.
                        </p>

                    </div>

                </div>

            </div>

            @elseif (!$periodeBerjalan)

            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/40 dark:bg-amber-900/20">

                <div class="flex gap-3">

                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v3m0 4h.01M10.29 3.86l-7.6 13.17A2 2 0 004.42 20h15.16a2 2 0 001.73 0 2 2 0 00-1.73-2.97l-7.6-13.17a2 2 0 00-3.42 0z" />
                        </svg>

                    </div>

                    <div>

                        <p class="font-semibold text-amber-900 dark:text-amber-200">
                            Periode Magang Tidak Aktif
                        </p>

                        <p class="mt-1 text-sm text-amber-800 dark:text-amber-300">
                            Anda belum dapat membuat logbook karena periode magang tidak sedang berjalan.
                        </p>

                    </div>

                </div>

            </div>

            @elseif ($logbookHariIni)

            {{-- =================================================
                         DATA LOGBOOK HARI INI
                         ================================================= --}}
            <div class="mt-5 rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Judul Kegiatan
                        </p>

                        <h4 class="mt-1 text-base font-bold text-gray-900 dark:text-gray-100">
                            {{ $logbookHariIni->judul_kegiatan }}
                        </h4>

                        <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-gray-300">
                            {{ $logbookHariIni->uraian_kegiatan }}
                        </p>

                    </div>

                </div>

                @if ($logbookHariIni->hasil_kegiatan)

                <div class="mt-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Hasil Kegiatan
                    </p>

                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {{ $logbookHariIni->hasil_kegiatan }}
                    </p>

                </div>

                @endif

                @if ($logbookHariIni->kendala)

                <div class="mt-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Kendala
                    </p>

                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {{ $logbookHariIni->kendala }}
                    </p>

                </div>

                @endif

                @if ($logbookHariIni->rencana_tindak_lanjut)

                <div class="mt-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Rencana Tindak Lanjut
                    </p>

                    <p class="mt-1 text-sm leading-6 text-gray-600 dark:text-gray-300">
                        {{ $logbookHariIni->rencana_tindak_lanjut }}
                    </p>

                </div>

                @endif

                @if ($logbookHariIni->catatan_mentor)

                <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">

                    <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-400">
                        Catatan Mentor
                    </p>

                    <p class="mt-1 text-sm leading-6 text-rose-800 dark:text-rose-300">
                        {{ $logbookHariIni->catatan_mentor }}
                    </p>

                </div>

                @endif

                {{-- =================================================
                             ACTION LOGBOOK
                             ================================================= --}}
                <div class="mt-5 flex flex-wrap gap-2">

                    @if ($logbookHariIni->status === 'draft')

                    <a
                        href="{{ route('mahasiswa.logbook.edit', $logbookHariIni) }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Edit Draft
                    </a>

                    <form
                        method="POST"
                        action="{{ route('mahasiswa.logbook.submit', $logbookHariIni) }}">
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
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

                            Submit ke Mentor
                        </button>
                    </form>

                    @elseif ($logbookHariIni->status === 'revision')

                    <a
                        href="{{ route('mahasiswa.logbook.edit', $logbookHariIni) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-700">
                        Perbaiki Logbook
                    </a>

                    <form
                        method="POST"
                        action="{{ route('mahasiswa.logbook.submit', $logbookHariIni) }}">
                        @csrf

                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl border border-rose-300 bg-white px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-900/50 dark:bg-gray-800 dark:text-rose-300 dark:hover:bg-rose-900/20">
                            Kirim Ulang ke Mentor
                        </button>
                    </form>

                    @endif

                </div>

            </div>

            @else

            {{-- =================================================
                         BELUM ADA LOGBOOK
                         ================================================= --}}
            <div class="mt-5 rounded-xl border border-emerald-200 bg-emerald-50 p-6 dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="font-semibold text-emerald-900 dark:text-emerald-200">
                            Belum ada logbook hari ini.
                        </p>

                        <p class="mt-1 text-sm text-emerald-800 dark:text-emerald-300">
                            Catat kegiatan magang yang Anda lakukan hari ini.
                        </p>

                    </div>

                    <a
                        href="{{ route('mahasiswa.logbook.create') }}"
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
                                d="M12 4v16m8-8H4" />
                        </svg>

                        Buat Logbook Hari Ini
                    </a>

                </div>

            </div>

            @endif

        </div>

        @else

        {{-- ========================================================
                 TIDAK ADA PENEMPATAN
                 ======================================================== --}}
        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 shadow-sm dark:border-amber-900/40 dark:bg-amber-900/20">

            <div class="flex gap-4">

                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">

                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v3m0 4h.01M10.29 3.86l-7.6 13.17A2 2 0 004.42 20h15.16a2 2 0 001.73-2.97l-7.6-13.17a2 2 0 00-3.42 0z" />
                    </svg>

                </div>

                <div>

                    <h3 class="font-bold text-amber-900 dark:text-amber-200">
                        Belum Ada Penempatan Magang
                    </h3>

                    <p class="mt-1 text-sm text-amber-800 dark:text-amber-300">
                        Anda belum memiliki penempatan magang aktif sehingga belum dapat membuat logbook.
                    </p>

                </div>

            </div>

        </div>

        @endif

        {{-- ============================================================
             RIWAYAT LOGBOOK
             ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                            Riwayat Logbook
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Daftar kegiatan magang yang telah Anda catat.
                        </p>

                    </div>

                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        {{ $logbooks->count() }} data
                    </span>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                No
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tanggal
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Kegiatan
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($logbooks as $logbook)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $loop->iteration }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $logbook->tanggal->translatedFormat('d F Y') }}
                            </td>

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $logbook->judul_kegiatan }}
                                </p>

                                <p class="mt-1 max-w-xl truncate text-sm text-gray-500 dark:text-gray-400">
                                    {{ $logbook->uraian_kegiatan }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-center">

                                @switch($logbook->status)

                                @case('draft')

                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    Draft
                                </span>

                                @break

                                @case('submitted')

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    Menunggu
                                </span>

                                @break

                                @case('approved')

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    Disetujui
                                </span>

                                @break

                                @case('revision')

                                <span class="inline-flex rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                    Revisi
                                </span>

                                @break

                                @endswitch

                            </td>

                            <td class="px-5 py-4 text-right">

                                @if ($logbook->status === 'draft')

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('mahasiswa.logbook.edit', $logbook) }}"
                                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                        Edit
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('mahasiswa.logbook.submit', $logbook) }}"
                                        class="inline">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-800">
                                            Submit
                                        </button>
                                    </form>

                                </div>

                                @elseif ($logbook->status === 'revision')

                                <div class="flex justify-end gap-2">

                                    <a
                                        href="{{ route('mahasiswa.logbook.edit', $logbook) }}"
                                        class="inline-flex items-center gap-2 rounded-lg bg-rose-600 px-3 py-2 text-xs font-semibold text-white transition hover:bg-rose-700">
                                        Perbaiki
                                    </a>

                                    <form
                                        method="POST"
                                        action="{{ route('mahasiswa.logbook.submit', $logbook) }}"
                                        class="inline">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg border border-rose-300 bg-white px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-50 dark:border-rose-900/50 dark:bg-gray-800 dark:text-rose-300 dark:hover:bg-rose-900/20">
                                            Kirim Ulang
                                        </button>
                                    </form>

                                </div>

                                @elseif ($logbook->status === 'submitted')

                                <span class="text-xs font-medium text-gray-400 dark:text-gray-500">
                                    Menunggu Mentor
                                </span>

                                @elseif ($logbook->status === 'approved')

                                <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">
                                    Selesai
                                </span>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-16 text-center">

                                <div class="mx-auto max-w-sm">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">

                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M8 7h8m-8 4h8m-8 4h5M5 3h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                        </svg>

                                    </div>

                                    <h4 class="mt-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Belum Ada Riwayat Logbook
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada kegiatan yang dicatat.
                                    </p>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-simaga-layout>