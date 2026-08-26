<x-simaga-layout>

    <x-slot:title>Progress Magang - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Progress Magang</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Aktivitas Magang
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                Progress Magang
            </h3>

            @if ($penempatan)

            <p class="mt-1 text-sm text-gray-500">
                {{ $penempatan->periodeMagang->nama_periode }}
            </p>

            @else

            <p class="mt-1 text-sm text-gray-500">
                Ringkasan perkembangan kegiatan magang Anda.
            </p>

            @endif

        </div>

        @if (! $penempatan)

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6">

            <h3 class="font-bold text-amber-900">
                Penempatan Belum Tersedia
            </h3>

            <p class="mt-1 text-sm leading-6 text-amber-800">
                Progress belum dapat ditampilkan karena Anda belum memiliki
                penempatan magang aktif.
            </p>

        </div>

        @else

        {{-- Overall Progress --}}
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                        Progress Keseluruhan
                    </p>

                    <p class="mt-1 text-sm text-emerald-800">
                        Ringkasan perkembangan kegiatan magang Anda.
                    </p>

                </div>

                <div class="text-left sm:text-right">

                    <p class="text-4xl font-bold text-emerald-800">
                        {{ number_format($persentaseKeseluruhan, 0) }}%
                    </p>

                </div>

            </div>

            <div class="mt-5 h-3 overflow-hidden rounded-full bg-emerald-100">

                <div
                    class="h-full rounded-full bg-emerald-700 transition-all"
                    style="width: {{ min($persentaseKeseluruhan, 100) }}%"></div>

            </div>

        </div>

        {{-- Absensi --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        01
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Absensi
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Kehadiran selama pelaksanaan magang.
                    </p>

                </div>

                <span class="text-2xl font-bold text-emerald-700">
                    {{ number_format($progress['absensi']['persentase'], 0) }}%
                </span>

            </div>

            <div class="mt-5 h-3 overflow-hidden rounded-full bg-gray-100">

                <div
                    class="h-full rounded-full bg-emerald-600"
                    style="width: {{ min($progress['absensi']['persentase'], 100) }}%"></div>

            </div>

            <div class="mt-4 flex justify-between text-sm text-gray-500">

                <span>
                    {{ $progress['absensi']['hadir'] }} hadir
                </span>

                <span>
                    {{ $progress['absensi']['total'] }} absensi
                </span>

            </div>

        </div>

        {{-- Logbook --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        02
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Logbook
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Kegiatan yang sudah diperiksa dan disetujui mentor.
                    </p>

                </div>

                <span class="text-2xl font-bold text-emerald-700">
                    {{ number_format($progress['logbook']['persentase'], 0) }}%
                </span>

            </div>

            <div class="mt-5 h-3 overflow-hidden rounded-full bg-gray-100">

                <div
                    class="h-full rounded-full bg-emerald-600"
                    style="width: {{ min($progress['logbook']['persentase'], 100) }}%"></div>

            </div>

            <div class="mt-4 flex justify-between text-sm text-gray-500">

                <span>
                    {{ $progress['logbook']['approved'] }} disetujui
                </span>

                <span>
                    {{ $progress['logbook']['total'] }} total
                </span>

            </div>

        </div>

        {{-- Tugas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        03
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Tugas
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        Tugas yang sudah dikumpulkan dan disetujui mentor.
                    </p>

                </div>

                <span class="text-2xl font-bold text-emerald-700">
                    {{ number_format($progress['tugas']['persentase'], 0) }}%
                </span>

            </div>

            <div class="mt-5 h-3 overflow-hidden rounded-full bg-gray-100">

                <div
                    class="h-full rounded-full bg-emerald-600"
                    style="width: {{ min($progress['tugas']['persentase'], 100) }}%"></div>

            </div>

            <div class="mt-4 flex justify-between text-sm text-gray-500">

                <span>
                    {{ $progress['tugas']['approved'] }} disetujui
                </span>

                <span>
                    {{ $progress['tugas']['total'] }} total
                </span>

            </div>

        </div>

        {{-- Penilaian --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        04
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Penilaian
                    </h3>

                </div>

                <a
                    href="{{ route('mahasiswa.penilaian.index') }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat →
                </a>

            </div>

            <div class="mt-5">

                @if (! $progress['penilaian']['tersedia'])

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="font-semibold text-gray-900">
                        Belum Ada Penilaian
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Mentor belum membuat penilaian.
                    </p>

                </div>

                @elseif (! $progress['penilaian']['final'])

                <div class="rounded-xl bg-amber-50 p-5">

                    <p class="font-semibold text-amber-900">
                        Penilaian Masih Dalam Proses
                    </p>

                    <p class="mt-1 text-sm text-amber-800">
                        Penilaian mentor belum difinalisasi.
                    </p>

                </div>

                @else

                <div class="flex items-center justify-between rounded-xl bg-emerald-50 p-5">

                    <div>

                        <p class="text-sm text-emerald-600">
                            Nilai Akhir
                        </p>

                        <p class="mt-1 text-3xl font-bold text-emerald-800">
                            {{ number_format($progress['penilaian']['nilai_akhir'], 2) }}
                        </p>

                    </div>

                    <span class="rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                        Final
                    </span>

                </div>

                @endif

            </div>

        </div>

        {{-- Sertifikat --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        05
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900">
                        Sertifikat
                    </h3>

                </div>

                <a
                    href="{{ route('mahasiswa.sertifikat.index') }}"
                    class="text-sm font-semibold text-emerald-700 hover:text-emerald-800">
                    Lihat →
                </a>

            </div>

            <div class="mt-5">

                @if ($progress['sertifikat']['tersedia'])

                <div class="rounded-xl bg-emerald-50 p-5">

                    <p class="font-semibold text-emerald-900">
                        Sertifikat Sudah Terbit
                    </p>

                    <p class="mt-1 text-sm text-emerald-800">
                        Sertifikat magang Anda sudah tersedia.
                    </p>

                </div>

                @else

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="font-semibold text-gray-900">
                        Belum Tersedia
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        Sertifikat belum diterbitkan oleh admin.
                    </p>

                </div>

                @endif

            </div>

        </div>

        @endif

    </div>

</x-simaga-layout>