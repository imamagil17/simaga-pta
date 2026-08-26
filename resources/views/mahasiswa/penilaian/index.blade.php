<x-simaga-layout>

    <x-slot:title>Penilaian Saya - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Penilaian Saya</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Hasil Evaluasi
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                Penilaian Magang
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Lihat hasil penilaian yang diberikan oleh mentor.
            </p>

        </div>

        @if (! $penilaian)

        <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-500">
                <svg
                    class="h-7 w-7"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" />
                </svg>
            </div>

            <h3 class="mt-4 font-bold text-gray-900 dark:text-gray-100">
                Penilaian Belum Tersedia
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Mentor belum membuat penilaian untuk Anda.
            </p>

        </div>

        @elseif ($penilaian->status !== 'final')

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 dark:border-amber-900/40 dark:bg-amber-900/20">

            <div class="flex items-start gap-4">

                <div class="mt-0.5 text-amber-600">
                    <svg
                        class="h-6 w-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 9v4m0 4h.01M10.29 3.86l-8.18 14a2 2 0 001.72 3h16.34a2 2 0 001.72-3l-8.18-14a2 2 0 00-3.42 0z" />
                    </svg>
                </div>

                <div>

                    <h3 class="font-bold text-amber-900">
                        Penilaian Masih Dalam Proses
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-amber-800">
                        Mentor sudah membuat penilaian, tetapi belum difinalisasi.
                    </p>

                </div>

            </div>

        </div>

        @else

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 dark:border-emerald-900/40 dark:bg-emerald-900/20">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                        Nilai Akhir
                    </p>

                    <p class="mt-2 text-5xl font-bold text-emerald-800">
                        {{ number_format((float) $penilaian->nilai_akhir, 2) }}
                    </p>

                    <p class="mt-2 text-sm text-emerald-700">
                        Penilaian telah difinalisasi oleh mentor.
                    </p>

                </div>

                <a
                    href="{{ route('mahasiswa.penilaian.show') }}"
                    class="inline-flex w-fit rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                    Lihat Detail
                </a>

            </div>

        </div>

        @endif

    </div>

</x-simaga-layout>