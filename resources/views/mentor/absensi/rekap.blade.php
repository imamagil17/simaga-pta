<x-simaga-layout>

    <x-slot:title>Rekap Absensi - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Rekap Absensi</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Bimbingan & Evaluasi
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Rekap Absensi Mahasiswa
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Ringkasan kehadiran seluruh mahasiswa yang Anda bimbing.
                </p>

            </div>

            <div class="flex flex-wrap gap-2">

                <a
                    href="{{ route('mentor.absensi.index') }}"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Daftar Absensi
                </a>

            </div>

        </div>

        {{-- Rekap keseluruhan --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Mahasiswa
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $rekap['total_mahasiswa'] }}
                </p>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Bimbingan aktif
                </p>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Hadir
                </p>

                <p class="mt-2 text-2xl font-bold text-emerald-800 dark:text-emerald-300">
                    {{ $rekap['hadir'] }}
                </p>

                <p class="mt-1 text-xs text-emerald-700 dark:text-emerald-400">
                    Absensi
                </p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm dark:border-amber-900/40 dark:bg-amber-900/20">
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

            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-5 shadow-sm dark:border-orange-900/40 dark:bg-orange-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-orange-600 dark:text-orange-400">
                    Total Terlambat
                </p>

                <p class="mt-2 text-2xl font-bold text-orange-800 dark:text-orange-300">
                    {{ $rekap['total_menit_terlambat'] }}
                </p>

                <p class="mt-1 text-xs text-orange-700 dark:text-orange-400">
                    Menit
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900/40">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Menunggu
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-gray-200">
                    {{ $rekap['pending'] }}
                </p>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Verifikasi
                </p>
            </div>

        </div>

        {{-- Statistik tambahan --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-900/40 dark:bg-blue-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                    Izin
                </p>

                <p class="mt-2 text-2xl font-bold text-blue-800 dark:text-blue-300">
                    {{ $rekap['izin'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-purple-200 bg-purple-50 p-5 dark:border-purple-900/40 dark:bg-purple-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-purple-600 dark:text-purple-400">
                    Sakit
                </p>

                <p class="mt-2 text-2xl font-bold text-purple-800 dark:text-purple-300">
                    {{ $rekap['sakit'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 dark:border-rose-900/40 dark:bg-rose-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-400">
                    Alpa
                </p>

                <p class="mt-2 text-2xl font-bold text-rose-800 dark:text-rose-300">
                    {{ $rekap['alpa'] }}
                </p>
            </div>

        </div>

        {{-- Tabel mahasiswa --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Rekap Per Mahasiswa
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Statistik absensi masing-masing mahasiswa bimbingan.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Mahasiswa
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Hadir
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Izin
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Sakit
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Alpa
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Terlambat
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Menit
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Pending
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($mahasiswaRekap as $item)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            <td class="px-5 py-4">

                                <div class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $item['mahasiswa']->user->name }}
                                </div>

                                <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item['mahasiswa']->nim }}
                                </div>

                            </td>

                            <td class="px-4 py-4 text-center text-sm font-semibold text-emerald-700 dark:text-emerald-400">
                                {{ $item['hadir'] }}
                            </td>

                            <td class="px-4 py-4 text-center text-sm font-semibold text-blue-700 dark:text-blue-400">
                                {{ $item['izin'] }}
                            </td>

                            <td class="px-4 py-4 text-center text-sm font-semibold text-purple-700 dark:text-purple-400">
                                {{ $item['sakit'] }}
                            </td>

                            <td class="px-4 py-4 text-center text-sm font-semibold text-rose-700 dark:text-rose-400">
                                {{ $item['alpa'] }}
                            </td>

                            <td class="px-4 py-4 text-center text-sm font-semibold text-amber-700 dark:text-amber-400">
                                {{ $item['terlambat'] }}
                            </td>

                            <td class="px-4 py-4 text-center text-sm font-semibold text-orange-700 dark:text-orange-400">
                                {{ $item['total_menit_terlambat'] }}
                            </td>

                            <td class="px-4 py-4 text-center">

                                @if ($item['pending'] > 0)

                                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    {{ $item['pending'] }}
                                </span>

                                @else

                                <span class="text-sm text-gray-400 dark:text-gray-500">
                                    -
                                </span>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="8" class="px-6 py-16 text-center">

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
                                        Belum Ada Data Absensi
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Belum ada absensi mahasiswa bimbingan yang tercatat.
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