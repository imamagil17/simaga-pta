<x-simaga-layout>

    <x-slot:title>Detail Sertifikat - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Sertifikat</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        <div>

            <a
                href="{{ route('mahasiswa.sertifikat.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Sertifikat
            </a>

        </div>

        @if (! $sertifikat)

        <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">

            <h3 class="font-bold text-gray-900">
                Sertifikat Belum Tersedia
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Belum ada sertifikat yang diterbitkan untuk Anda.
            </p>

        </div>

        @else

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Sertifikat Magang
                    </p>

                    <h3 class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $sertifikat->nomor_sertifikat }}
                    </h3>

                </div>

                <span class="inline-flex w-fit rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold text-emerald-800">
                    Disetujui
                </span>

            </div>

        </div>

        {{-- Informasi --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h3 class="text-lg font-bold text-gray-900">
                Informasi Sertifikat
            </h3>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Nama Mahasiswa
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $sertifikat->mahasiswa->user->name }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        NIM
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $sertifikat->mahasiswa->nim }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Perguruan Tinggi
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $sertifikat->mahasiswa->perguruan_tinggi }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Program Studi
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $sertifikat->mahasiswa->program_studi }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mentor
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $sertifikat->mentor->user->name }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $sertifikat->penempatan->periodeMagang->nama_periode }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Nomor Sertifikat
                    </p>

                    <p class="mt-1 font-bold text-emerald-700">
                        {{ $sertifikat->nomor_sertifikat }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Tanggal Terbit
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $sertifikat->approved_at?->translatedFormat('d F Y') }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Aksi --}}
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

            <h3 class="font-bold text-emerald-900">
                Sertifikat Anda Sudah Tersedia
            </h3>

            <p class="mt-1 text-sm leading-6 text-emerald-800">
                Silakan download sertifikat dalam bentuk PDF.
            </p>

            <div class="mt-5">

                <a
                    href="{{ route('mahasiswa.sertifikat.download') }}"
                    class="inline-flex rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                    Download Sertifikat PDF
                </a>

            </div>

        </div>

        @endif

    </div>

</x-simaga-layout>