<x-simaga-layout>

    <x-slot:title>Detail Mahasiswa - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Mahasiswa Bimbingan</x-slot:headerTitle>

    <div class="mx-auto max-w-6xl space-y-6">

        <div>

            <a
                href="{{ route('mentor.mahasiswa-bimbingan.index') }}"
                class="text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Mahasiswa Bimbingan
            </a>

        </div>

        {{-- Identitas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mahasiswa
                    </p>

                    <h3 class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $penempatan->mahasiswa->user->name }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $penempatan->mahasiswa->nim }}
                    </p>

                    <p class="mt-2 text-sm text-gray-600">
                        {{ $penempatan->mahasiswa->perguruan_tinggi }}
                        — {{ $penempatan->mahasiswa->program_studi }}
                    </p>

                </div>

                <div class="text-left sm:text-right">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $penempatan->periodeMagang->nama_periode }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $penempatan->periodeMagang->tanggal_mulai }}
                        s/d
                        {{ $penempatan->periodeMagang->tanggal_selesai }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Absensi --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Absensi
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Ringkasan Kehadiran
                    </h3>

                </div>

                <a
                    href="{{ route('mentor.absensi.index') }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat Absensi →
                </a>

            </div>

            <div class="mt-5 grid grid-cols-1 gap-4 sm:grid-cols-3">

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="text-xs text-gray-500">
                        Total Absensi
                    </p>

                    <p class="mt-1 text-3xl font-bold text-gray-900">
                        {{ $absensi['total'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-emerald-50 p-5">

                    <p class="text-xs text-emerald-600">
                        Hadir
                    </p>

                    <p class="mt-1 text-3xl font-bold text-emerald-800">
                        {{ $absensi['hadir'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-amber-50 p-5">

                    <p class="text-xs text-amber-600">
                        Terlambat
                    </p>

                    <p class="mt-1 text-3xl font-bold text-amber-800">
                        {{ $absensi['terlambat'] }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Logbook --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Logbook
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Ringkasan Logbook
                    </h3>

                </div>

                <a
                    href="{{ route('mentor.logbook.index') }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat Logbook →
                </a>

            </div>

            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4">

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="text-xs text-gray-500">
                        Total
                    </p>

                    <p class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $logbook['total'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-emerald-50 p-5">

                    <p class="text-xs text-emerald-600">
                        Disetujui
                    </p>

                    <p class="mt-1 text-2xl font-bold text-emerald-800">
                        {{ $logbook['approved'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-amber-50 p-5">

                    <p class="text-xs text-amber-600">
                        Menunggu
                    </p>

                    <p class="mt-1 text-2xl font-bold text-amber-800">
                        {{ $logbook['submitted'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-rose-50 p-5">

                    <p class="text-xs text-rose-600">
                        Revisi
                    </p>

                    <p class="mt-1 text-2xl font-bold text-rose-800">
                        {{ $logbook['revision'] }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Tugas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Tugas
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Ringkasan Tugas
                    </h3>

                </div>

                <a
                    href="{{ route('mentor.tugas.index') }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat Tugas →
                </a>

            </div>

            <div class="mt-5 grid grid-cols-2 gap-4 sm:grid-cols-4">

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="text-xs text-gray-500">
                        Total
                    </p>

                    <p class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $tugas['total'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-emerald-50 p-5">

                    <p class="text-xs text-emerald-600">
                        Disetujui
                    </p>

                    <p class="mt-1 text-2xl font-bold text-emerald-800">
                        {{ $tugas['approved'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-amber-50 p-5">

                    <p class="text-xs text-amber-600">
                        Terkumpul
                    </p>

                    <p class="mt-1 text-2xl font-bold text-amber-800">
                        {{ $tugas['submitted'] }}
                    </p>

                </div>

                <div class="rounded-xl bg-rose-50 p-5">

                    <p class="text-xs text-rose-600">
                        Revisi
                    </p>

                    <p class="mt-1 text-2xl font-bold text-rose-800">
                        {{ $tugas['revision'] }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Penilaian --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Penilaian
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Hasil Evaluasi
                    </h3>

                </div>

                <a
                    href="{{ route('mentor.penilaian.show', $penempatan->mahasiswa) }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat Penilaian →
                </a>

            </div>

            <div class="mt-5 rounded-2xl bg-emerald-50 p-6 text-center">

                @if ($penilaian)

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    Nilai Akhir
                </p>

                <p class="mt-2 text-5xl font-bold text-emerald-800">
                    {{ number_format((float) $penilaian->nilai_akhir, 2) }}
                </p>

                @if ($penilaian->status === 'final')

                <span class="mt-3 inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                    Final
                </span>

                @else

                <span class="mt-3 inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                    Draft
                </span>

                @endif

                @else

                <p class="font-semibold text-gray-500">
                    Belum ada penilaian
                </p>

                @endif

            </div>

        </div>

        {{-- Sertifikat --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Sertifikat
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Status Sertifikat
                    </h3>

                </div>

                <a
                    href="{{ route('mentor.sertifikat.show', $penempatan->mahasiswa) }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat Sertifikat →
                </a>

            </div>

            <div class="mt-5">

                @if (! $sertifikat)

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="font-semibold text-gray-900">
                        Belum Diajukan
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Pengajuan sertifikat belum dibuat.
                    </p>

                </div>

                @elseif ($sertifikat->status === 'pending')

                <div class="rounded-xl bg-amber-50 p-5">

                    <p class="font-semibold text-amber-900">
                        Menunggu Persetujuan
                    </p>

                    <p class="mt-1 text-sm text-amber-800">
                        Pengajuan sedang diperiksa admin.
                    </p>

                </div>

                @elseif ($sertifikat->status === 'approved')

                <div class="rounded-xl bg-emerald-50 p-5">

                    <p class="font-semibold text-emerald-900">
                        Sertifikat Terbit
                    </p>

                    <p class="mt-1 text-sm text-emerald-800">
                        {{ $sertifikat->nomor_sertifikat }}
                    </p>

                </div>

                @else

                <div class="rounded-xl bg-rose-50 p-5">

                    <p class="font-semibold text-rose-900">
                        Pengajuan Ditolak
                    </p>

                    @if ($sertifikat->catatan)

                    <p class="mt-1 text-sm text-rose-800">
                        {{ $sertifikat->catatan }}
                    </p>

                    @endif

                </div>

                @endif

            </div>

        </div>

    </div>

</x-simaga-layout>