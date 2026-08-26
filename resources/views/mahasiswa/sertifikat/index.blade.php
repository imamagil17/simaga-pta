<x-simaga-layout>

    <x-slot:title>Sertifikat Magang - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Sertifikat Magang</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                Administrasi Magang
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                Sertifikat Magang
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Sertifikat yang telah diterbitkan oleh Pengadilan Tinggi Agama Palu.
            </p>

        </div>

        @if (! $sertifikat)

        <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-gray-100 text-gray-500 dark:bg-gray-700">

                <svg
                    class="h-8 w-8"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h7l5 5v11a2 2 0 01-2 2z" />
                </svg>

            </div>

            <h3 class="mt-5 font-bold text-gray-900 dark:text-gray-100">
                Sertifikat Belum Tersedia
            </h3>

            <p class="mx-auto mt-2 max-w-md text-sm leading-6 text-gray-500">
                Sertifikat Anda belum diterbitkan oleh admin.
                Setelah pengajuan mentor disetujui, sertifikat akan muncul di halaman ini.
            </p>

        </div>

        @else

        {{-- Sertifikat tersedia --}}
        <div class="overflow-hidden rounded-2xl border border-emerald-200 bg-white shadow-sm">

            <div class="border-b border-emerald-100 bg-emerald-50 p-6">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                            Sertifikat Diterbitkan
                        </p>

                        <h3 class="mt-1 text-2xl font-bold text-emerald-900">
                            Sertifikat Magang
                        </h3>

                    </div>

                    <span class="inline-flex w-fit rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold text-emerald-800">
                        Disetujui
                    </span>

                </div>

            </div>

            <div class="p-6">

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Nama Mahasiswa
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-900">
                            {{ $sertifikat->mahasiswa->user->name }}
                        </p>

                    </div>

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            NIM
                        </p>

                        <p class="mt-1 text-lg font-bold text-gray-900">
                            {{ $sertifikat->mahasiswa->nim }}
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
                            Periode Magang
                        </p>

                        <p class="mt-1 font-semibold text-gray-900">
                            {{ $sertifikat->penempatan->periodeMagang->nama_periode }}
                        </p>

                    </div>

                    <div class="sm:col-span-2">

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Nomor Sertifikat
                        </p>

                        <p class="mt-1 text-lg font-bold text-emerald-700">
                            {{ $sertifikat->nomor_sertifikat }}
                        </p>

                    </div>

                </div>

                <div class="mt-6 flex flex-wrap gap-3 border-t border-gray-100 pt-6">

                    <a
                        href="{{ route('mahasiswa.sertifikat.show') }}"
                        class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Lihat Sertifikat
                    </a>

                    <a
                        href="{{ route('mahasiswa.sertifikat.download') }}"
                        class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                        Download PDF
                    </a>

                </div>

            </div>

        </div>

        @endif

    </div>

</x-simaga-layout>