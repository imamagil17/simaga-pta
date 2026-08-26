<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');

    $submittedCount = $tugas->pengumpulan
    ->where('status', 'submitted')
    ->count();

    $reviewedCount = $tugas->pengumpulan
    ->where('status', 'reviewed')
    ->count();

    $revisionCount = $tugas->pengumpulan
    ->where('status', 'revision')
    ->count();

    $draftCount = $tugas->pengumpulan
    ->where('status', 'draft')
    ->count();
    @endphp

    <x-slot:title>Detail Tugas - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Tugas</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- ============================================================
             KEMBALI
        ============================================================ --}}
        <div>

            <a
                href="{{ route('admin.tugas.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition hover:text-emerald-700 dark:text-gray-400 dark:hover:text-emerald-400">

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

                Kembali ke Monitoring Tugas

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
                        {{ $tugas->penempatan->mahasiswa->user->name ?? '-' }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ $tugas->penempatan->mahasiswa->nim ?? '-' }}
                    </p>

                </div>

                {{-- Mentor --}}
                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Mentor
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $tugas->penempatan->mentor->user->name ?? '-' }}
                    </p>

                </div>

                {{-- Periode --}}
                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Periode Magang
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $tugas->penempatan->periodeMagang->nama_periode ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- ============================================================
             DETAIL TUGAS
        ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Judul Tugas
                    </p>

                    <h3 class="mt-1 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $tugas->judul }}
                    </h3>

                </div>

                {{-- Status --}}
                @switch($tugas->status)

                @case('draft')

                <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    Draft
                </span>

                @break

                @case('published')

                <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                    Published
                </span>

                @break

                @case('closed')

                <span class="inline-flex w-fit rounded-full bg-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    Closed
                </span>

                @break

                @endswitch

            </div>

            {{-- Deskripsi --}}
            <div class="mt-6">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Deskripsi / Instruksi
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-gray-300">
                    {{ $tugas->deskripsi }}
                </p>

            </div>

            {{-- Waktu --}}
            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Tanggal Mulai
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $tugas->tanggal_mulai->translatedFormat('l, d F Y, H:i') }} WITA
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Deadline
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $tugas->tanggal_deadline->translatedFormat('l, d F Y, H:i') }} WITA
                    </p>

                </div>

            </div>

            {{-- Pembuat --}}
            <div class="mt-6 border-t border-gray-100 pt-6 dark:border-gray-700">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Dibuat Oleh
                </p>

                <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                    {{ $tugas->creator->name ?? '-' }}
                </p>

            </div>

            {{-- File --}}
            @if ($tugas->file_tugas)

            <div class="mt-6 border-t border-gray-100 pt-6 dark:border-gray-700">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    File Tugas
                </p>

                <a
                    href="{{ asset('storage/' . $tugas->file_tugas) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-3 inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828L18 9.828a4 4 0 10-5.657-5.657l-7.07 7.07a6 6 0 108.486 8.486L20 13.172" />
                    </svg>

                    Buka File Tugas

                </a>

            </div>

            @endif

        </div>

        {{-- ============================================================
             REKAP PENGUMPULAN
        ============================================================ --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Draft
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $draftCount }}
                </p>

            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm dark:border-amber-900/40 dark:bg-amber-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                    Menunggu
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-800 dark:text-amber-300">
                    {{ $submittedCount }}
                </p>

            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Dinilai
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800 dark:text-emerald-300">
                    {{ $reviewedCount }}
                </p>

            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm dark:border-rose-900/40 dark:bg-rose-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-400">
                    Revisi
                </p>

                <p class="mt-2 text-3xl font-bold text-rose-800 dark:text-rose-300">
                    {{ $revisionCount }}
                </p>

            </div>

        </div>

        {{-- ============================================================
             PENGUMPULAN
        ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Riwayat Pengumpulan
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Status pengumpulan mahasiswa pada tugas ini.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Status
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Nilai
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Dikumpulkan
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($tugas->pengumpulan as $pengumpulan)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            {{-- Mahasiswa --}}
                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $pengumpulan->mahasiswa->user->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $pengumpulan->mahasiswa->nim ?? '-' }}
                                </p>

                            </td>

                            {{-- Status --}}
                            <td class="px-5 py-4 text-center">

                                @switch($pengumpulan->status)

                                @case('draft')

                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    Draft
                                </span>

                                @break

                                @case('submitted')

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    Menunggu Pemeriksaan
                                </span>

                                @break

                                @case('reviewed')

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    Sudah Dinilai
                                </span>

                                @break

                                @case('revision')

                                <span class="inline-flex rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                    Perlu Revisi
                                </span>

                                @break

                                @default

                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                                    {{ ucfirst($pengumpulan->status) }}
                                </span>

                                @endswitch

                            </td>

                            {{-- Nilai --}}
                            <td class="px-5 py-4 text-center">

                                @if ($pengumpulan->nilai !== null)

                                <span class="font-bold text-gray-900 dark:text-gray-100">
                                    {{ number_format((float) $pengumpulan->nilai, 2) }}
                                </span>

                                @else

                                <span class="text-sm text-gray-400">
                                    -
                                </span>

                                @endif

                            </td>

                            {{-- Tanggal --}}
                            <td class="px-5 py-4 text-sm text-gray-600 dark:text-gray-300">

                                {{ $pengumpulan->dikumpulkan_at?->translatedFormat('d F Y, H:i') ?? '-' }}

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-14 text-center">

                                <div class="mx-auto max-w-sm">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">

                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M8 7h8M8 11h8M8 15h5M5 3h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                        </svg>

                                    </div>

                                    <h4 class="mt-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Belum Ada Pengumpulan
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Mahasiswa belum mengumpulkan tugas ini.
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