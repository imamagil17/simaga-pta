<x-simaga-layout>

    <x-slot:title>Dashboard Administrator - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Dashboard Administrator</x-slot:headerTitle>

    <div class="space-y-6">

        {{-- ============================================================
             HEADER
             ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                Sistem Informasi Magang
            </p>

            <h3 class="mt-1 text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                Dashboard Administrator
            </h3>

            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                Ringkasan kondisi data magang dan absensi mahasiswa secara keseluruhan.
            </p>

        </div>

        {{-- ============================================================
             STATISTIK DATA MAGANG
             ============================================================ --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            {{-- Mahasiswa --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Mahasiswa Aktif
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $mahasiswaAktif }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Akun mahasiswa aktif
                        </p>

                    </div>

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
                                d="M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m7-6a4 4 0 11-8 0 4 4 0 018 0zm6 1a3 3 0 11-6 0" />
                        </svg>

                    </div>

                </div>

            </div>

            {{-- Mentor --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Mentor Aktif
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $mentorAktif }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Mentor aktif
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 14a7 7 0 007-7M12 14a7 7 0 01-7-7m7 7v6m0-6V7m0 13H8m4 0h4M9 7a3 3 0 116 0 3 3 0 01-6 0z" />
                        </svg>

                    </div>

                </div>

            </div>

            {{-- Periode --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Periode Aktif
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $periodeAktif }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Periode magang aktif
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">

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

                </div>

            </div>

            {{-- Penempatan --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                            Penempatan Aktif
                        </p>

                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $penempatanAktif }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Mahasiswa ditempatkan
                        </p>

                    </div>

                    <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 20l-5-2V6l5 2 6-2 5 2v12l-5-2-6 2zm0 0V8m6-2v12" />
                        </svg>

                    </div>

                </div>

            </div>

        </div>

        {{-- ============================================================
             ABSENSI HARI INI
             ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                        Monitoring Absensi
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">
                        Absensi Hari Ini
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ now()->translatedFormat('l, d F Y') }}
                    </p>

                </div>

                <a
                    href="{{ route('admin.absensi.index') }}"
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                    Lihat Monitoring
                </a>

            </div>

            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Total
                    </p>

                    <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $absensiRekap['total'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                    <p class="text-xs text-emerald-600 dark:text-emerald-400">
                        Hadir
                    </p>

                    <p class="mt-2 text-2xl font-bold text-emerald-800 dark:text-emerald-300">
                        {{ $absensiRekap['hadir'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/40 dark:bg-amber-900/20">
                    <p class="text-xs text-amber-600 dark:text-amber-400">
                        Terlambat
                    </p>

                    <p class="mt-2 text-2xl font-bold text-amber-800 dark:text-amber-300">
                        {{ $absensiRekap['terlambat'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/40 dark:bg-blue-900/20">
                    <p class="text-xs text-blue-600 dark:text-blue-400">
                        Menunggu
                    </p>

                    <p class="mt-2 text-2xl font-bold text-blue-800 dark:text-blue-300">
                        {{ $absensiRekap['pending'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                    <p class="text-xs text-emerald-600 dark:text-emerald-400">
                        Disetujui
                    </p>

                    <p class="mt-2 text-2xl font-bold text-emerald-800 dark:text-emerald-300">
                        {{ $absensiRekap['approved'] }}
                    </p>
                </div>

                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">
                    <p class="text-xs text-rose-600 dark:text-rose-400">
                        Ditolak
                    </p>

                    <p class="mt-2 text-2xl font-bold text-rose-800 dark:text-rose-300">
                        {{ $absensiRekap['rejected'] }}
                    </p>
                </div>

            </div>

            @if ($absensiRekap['total_menit_terlambat'] > 0)

            <div class="mt-4 rounded-xl border border-orange-200 bg-orange-50 p-4 text-sm text-orange-800 dark:border-orange-900/40 dark:bg-orange-900/20 dark:text-orange-300">

                Total keterlambatan hari ini:
                <strong>
                    {{ $absensiRekap['total_menit_terlambat'] }} menit
                </strong>

            </div>

            @endif

        </div>

        {{-- ============================================================
             ABSENSI MENUNGGU VERIFIKASI
             ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <div class="flex items-center justify-between">

                    <div>

                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                            Menunggu Verifikasi
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Absensi terbaru yang belum diperiksa mentor.
                        </p>

                    </div>

                    @if ($absensiPending->count() > 0)

                    <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                        {{ $absensiPending->count() }} data
                    </span>

                    @endif

                </div>

            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-700">

                @forelse ($absensiPending as $absensi)

                <div class="flex flex-col gap-4 px-6 py-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="font-semibold text-gray-900 dark:text-gray-100">
                            {{ $absensi->penempatan->mahasiswa->user->name }}
                        </p>

                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ $absensi->penempatan->mahasiswa->nim }}
                            ·
                            {{ $absensi->tanggal->translatedFormat('d F Y') }}
                        </p>

                    </div>

                    <div class="flex items-center gap-4">

                        <div class="text-right">

                            <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                Masuk
                                {{ \Illuminate\Support\Str::substr($absensi->jam_masuk ?? '--:--', 0, 5) }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                Mentor:
                                {{ $absensi->penempatan->mentor->user->name }}
                            </p>

                        </div>

                        <a
                            href="{{ route('admin.absensi.show', $absensi) }}"
                            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                            Detail
                        </a>

                    </div>

                </div>

                @empty

                <div class="px-6 py-12 text-center">

                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">

                        <svg
                            class="h-6 w-6"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M5 13l4 4L19 7" />
                        </svg>

                    </div>

                    <p class="mt-3 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        Tidak ada absensi menunggu verifikasi.
                    </p>

                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Semua absensi terbaru sudah diproses.
                    </p>

                </div>

                @endforelse

            </div>

        </div>

        {{-- ============================================================
             MAHASISWA TERBARU
             ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <div>

                    <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Mahasiswa Terbaru
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Mahasiswa aktif yang terakhir ditambahkan.
                    </p>

                </div>

                <a
                    href="{{ route('admin.mahasiswa.index') }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-300">
                    Lihat Semua
                </a>

            </div>

            <div class="divide-y divide-gray-100 dark:divide-gray-700">

                @forelse ($mahasiswaTerbaru as $mahasiswa)

                <div class="flex items-center justify-between px-6 py-4">

                    <div class="flex items-center gap-3">

                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-emerald-100 text-sm font-bold text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                            {{ strtoupper(substr($mahasiswa->user->name, 0, 2)) }}
                        </div>

                        <div>

                            <p class="font-semibold text-gray-900 dark:text-gray-100">
                                {{ $mahasiswa->user->name }}
                            </p>

                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                {{ $mahasiswa->nim }}
                                ·
                                {{ $mahasiswa->program_studi }}
                            </p>

                        </div>

                    </div>

                    <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                        Aktif
                    </span>

                </div>

                @empty

                <div class="px-6 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                    Belum ada mahasiswa aktif.
                </div>

                @endforelse

            </div>

        </div>

    </div>

</x-simaga-layout>