<x-simaga-layout>

    <x-slot:title>Riwayat Absensi - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Riwayat Absensi</x-slot:headerTitle>

    <div class="mx-auto max-w-6xl space-y-6">

        {{-- ================================================================
             HEADER
             ================================================================ --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Aktivitas Magang
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Riwayat Absensi
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Rekap seluruh absensi Anda selama kegiatan magang.
                </p>

            </div>

            <a
                href="{{ route('mahasiswa.absensi.index') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
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

                Absensi Hari Ini
            </a>

        </div>

        {{-- ================================================================
             INFORMASI PENEMPATAN
             ================================================================ --}}
        @if ($penempatan)

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Periode
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->periodeMagang->nama_periode }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Mentor
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->mentor->user->name }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Total Absensi
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $absensis->count() }} hari
                    </p>

                </div>

            </div>

        </div>

        @endif

        {{-- ================================================================
             REKAP KEHADIRAN
             ================================================================ --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">

            {{-- Total --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Total
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $rekap['total'] }}
                </p>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Hari tercatat
                </p>

            </div>

            {{-- Hadir --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Hadir
                </p>

                <p class="mt-2 text-2xl font-bold text-emerald-800 dark:text-emerald-300">
                    {{ $rekap['hadir'] }}
                </p>

                <p class="mt-1 text-xs text-emerald-700 dark:text-emerald-400">
                    Hari
                </p>

            </div>

            {{-- Izin --}}
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 shadow-sm dark:border-blue-900/40 dark:bg-blue-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                    Izin
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-800 dark:text-blue-300">
                    {{ $rekap['izin'] }}
                </p>

                <p class="mt-1 text-xs text-blue-700 dark:text-blue-400">
                    Hari
                </p>

            </div>

            {{-- Sakit --}}
            <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5 shadow-sm dark:border-purple-900/40 dark:bg-purple-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-purple-600 dark:text-purple-400">
                    Sakit
                </p>

                <p class="mt-2 text-2xl font-bold text-purple-800 dark:text-purple-300">
                    {{ $rekap['sakit'] }}
                </p>

                <p class="mt-1 text-xs text-purple-700 dark:text-purple-400">
                    Hari
                </p>

            </div>

            {{-- Alpa --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm dark:border-rose-900/40 dark:bg-rose-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-400">
                    Alpa
                </p>

                <p class="mt-2 text-2xl font-bold text-rose-800 dark:text-rose-300">
                    {{ $rekap['alpa'] }}
                </p>

                <p class="mt-1 text-xs text-rose-700 dark:text-rose-400">
                    Hari
                </p>

            </div>

        </div>

        {{-- ================================================================
             REKAP KETERLAMBATAN & VERIFIKASI
             ================================================================ --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Jumlah terlambat --}}
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm dark:border-amber-900/40 dark:bg-amber-900/20">

                <div class="flex items-center justify-between gap-3">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                            Terlambat
                        </p>

                        <p class="mt-2 text-2xl font-bold text-amber-800 dark:text-amber-300">
                            {{ $rekap['terlambat'] }}
                        </p>

                        <p class="mt-1 text-xs text-amber-700 dark:text-amber-400">
                            Kejadian
                        </p>

                    </div>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">

                        <svg
                            class="h-5 w-5"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>

                    </div>

                </div>

            </div>

            {{-- Total menit terlambat --}}
            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-5 shadow-sm dark:border-orange-900/40 dark:bg-orange-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-orange-600 dark:text-orange-400">
                    Total Keterlambatan
                </p>

                <p class="mt-2 text-2xl font-bold text-orange-800 dark:text-orange-300">
                    {{ $rekap['total_menit_terlambat'] }}
                </p>

                <p class="mt-1 text-xs text-orange-700 dark:text-orange-400">
                    Menit
                </p>

            </div>

            {{-- Disetujui --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Disetujui
                </p>

                <p class="mt-2 text-2xl font-bold text-emerald-800 dark:text-emerald-300">
                    {{ $rekap['approved'] }}
                </p>

                <p class="mt-1 text-xs text-emerald-700 dark:text-emerald-400">
                    Absensi
                </p>

            </div>

            {{-- Menunggu --}}
            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900/40">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Menunggu
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-gray-200">
                    {{ $rekap['pending'] }}
                </p>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Verifikasi mentor
                </p>

            </div>

        </div>

        {{-- ================================================================
             TABEL RIWAYAT
             ================================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                            Riwayat Kehadiran
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Daftar seluruh absensi yang telah tercatat.
                        </p>

                    </div>

                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        {{ $absensis->count() }} data
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
                                Masuk
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Pulang
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Kehadiran
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Terlambat
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Verifikasi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($absensis as $absensi)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-500 dark:text-gray-400">
                                {{ $loop->iteration }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $absensi->tanggal->translatedFormat('d F Y') }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ \Illuminate\Support\Str::substr($absensi->jam_masuk ?? '--:--', 0, 5) }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ \Illuminate\Support\Str::substr($absensi->jam_pulang ?? '--:--', 0, 5) }}
                            </td>

                            <td class="px-5 py-4 text-center">

                                @if ($absensi->status_kehadiran === 'hadir')

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                    Hadir
                                </span>

                                @elseif ($absensi->status_kehadiran === 'izin')

                                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                    Izin
                                </span>

                                @elseif ($absensi->status_kehadiran === 'sakit')

                                <span class="inline-flex items-center rounded-full bg-purple-100 px-3 py-1.5 text-xs font-semibold text-purple-800 dark:bg-purple-900/40 dark:text-purple-300">
                                    Sakit
                                </span>

                                @else

                                <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                    Alpa
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-center">

                                @if ($absensi->menit_terlambat !== null)

                                <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    {{ $absensi->menit_terlambat }} menit
                                </span>

                                @else

                                <span class="text-sm text-gray-400 dark:text-gray-500">
                                    -
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-center">

                                @if ($absensi->status_verifikasi === 'pending')

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    Menunggu
                                </span>

                                @elseif ($absensi->status_verifikasi === 'approved')

                                <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    Disetujui
                                </span>

                                @else

                                <span class="inline-flex items-center rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                    Ditolak
                                </span>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7" class="px-6 py-16 text-center">

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
                                        Belum Ada Riwayat Absensi
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada data absensi yang tercatat.
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