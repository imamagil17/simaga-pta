<x-simaga-layout>

    <x-slot:title>Detail Laporan Magang - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Laporan Magang</x-slot:headerTitle>

    <div class="mx-auto max-w-6xl space-y-6">

        {{-- Navigasi --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

            <a
                href="{{ route('admin.laporan.index') }}"
                class="text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Laporan
            </a>

            <a
                href="{{ route('admin.laporan.pdf', $laporan['penempatan']) }}"
                class="inline-flex w-fit items-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                Download PDF
            </a>

        </div>

        {{-- Identitas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Mahasiswa
                    </p>

                    <p class="mt-1 text-xl font-bold text-gray-900">
                        {{ $laporan['mahasiswa']['nama'] }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $laporan['mahasiswa']['nim'] }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Mentor
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $laporan['mentor'] }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $laporan['periode'] }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Absensi --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h3 class="text-lg font-bold text-gray-900">
                Rekap Absensi
            </h3>

            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4">

                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs text-gray-500">
                        Total
                    </p>

                    <p class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $laporan['absensi']['total'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-emerald-50 p-4">

                    <p class="text-xs text-emerald-600">
                        Hadir
                    </p>

                    <p class="mt-1 text-2xl font-bold text-emerald-800">
                        {{ $laporan['absensi']['hadir'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-amber-50 p-4">

                    <p class="text-xs text-amber-600">
                        Terlambat
                    </p>

                    <p class="mt-1 text-2xl font-bold text-amber-800">
                        {{ $laporan['absensi']['terlambat'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-rose-50 p-4">

                    <p class="text-xs text-rose-600">
                        Menit Terlambat
                    </p>

                    <p class="mt-1 text-2xl font-bold text-rose-800">
                        {{ $laporan['absensi']['total_menit_terlambat'] }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Logbook --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h3 class="text-lg font-bold text-gray-900">
                Rekap Logbook
            </h3>

            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4">

                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs text-gray-500">
                        Total
                    </p>

                    <p class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $laporan['logbook']['total'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-emerald-50 p-4">

                    <p class="text-xs text-emerald-600">
                        Disetujui
                    </p>

                    <p class="mt-1 text-2xl font-bold text-emerald-800">
                        {{ $laporan['logbook']['approved'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-amber-50 p-4">

                    <p class="text-xs text-amber-600">
                        Menunggu
                    </p>

                    <p class="mt-1 text-2xl font-bold text-amber-800">
                        {{ $laporan['logbook']['submitted'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-rose-50 p-4">

                    <p class="text-xs text-rose-600">
                        Revisi
                    </p>

                    <p class="mt-1 text-2xl font-bold text-rose-800">
                        {{ $laporan['logbook']['revision'] }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Tugas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h3 class="text-lg font-bold text-gray-900">
                Rekap Tugas
            </h3>

            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4">

                <div class="rounded-xl bg-gray-50 p-4">

                    <p class="text-xs text-gray-500">
                        Total Tugas
                    </p>

                    <p class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $laporan['tugas']['total'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-emerald-50 p-4">

                    <p class="text-xs text-emerald-600">
                        Disetujui
                    </p>

                    <p class="mt-1 text-2xl font-bold text-emerald-800">
                        {{ $laporan['tugas']['approved'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-amber-50 p-4">

                    <p class="text-xs text-amber-600">
                        Terkumpul
                    </p>

                    <p class="mt-1 text-2xl font-bold text-amber-800">
                        {{ $laporan['tugas']['submitted'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-rose-50 p-4">

                    <p class="text-xs text-rose-600">
                        Revisi
                    </p>

                    <p class="mt-1 text-2xl font-bold text-rose-800">
                        {{ $laporan['tugas']['revision'] }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Penilaian --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h3 class="text-lg font-bold text-gray-900">
                Penilaian
            </h3>

            <div class="mt-5 rounded-2xl bg-emerald-50 p-6 text-center">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    Nilai Akhir
                </p>

                @if ($laporan['penilaian']['nilai_akhir'] !== null)

                <p class="mt-2 text-6xl font-bold text-emerald-800">
                    {{ number_format($laporan['penilaian']['nilai_akhir'], 2) }}
                </p>

                <p class="mt-2 text-sm text-emerald-700">

                    Status:

                    {{
                            $laporan['penilaian']['status'] === 'final'
                                ? 'Final'
                                : 'Draft'
                        }}

                </p>

                @else

                <p class="mt-2 text-lg font-semibold text-gray-400">
                    Belum ada penilaian
                </p>

                @endif

            </div>

        </div>

    </div>

</x-simaga-layout>