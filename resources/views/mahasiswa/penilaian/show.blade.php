<x-simaga-layout>

    <x-slot:title>Detail Penilaian - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Penilaian</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        <div>

            <a
                href="{{ route('mahasiswa.penilaian.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Penilaian
            </a>

        </div>

        @if (! $penilaian)

        <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center shadow-sm">

            <h3 class="font-bold text-gray-900">
                Penilaian Belum Tersedia
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Mentor belum membuat penilaian.
            </p>

        </div>

        @elseif ($penilaian->status !== 'final')

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6">

            <h3 class="font-bold text-amber-900">
                Penilaian Belum Final
            </h3>

            <p class="mt-1 text-sm text-amber-800">
                Nilai sementara belum dapat ditampilkan.
            </p>

        </div>

        @else

        {{-- Identitas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mahasiswa
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $penilaian->mahasiswa->user->name }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $penilaian->mahasiswa->nim }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mentor
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $penilaian->mentor->user->name }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $penilaian->penempatan->periodeMagang->nama_periode }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Nilai --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h3 class="text-lg font-bold text-gray-900">
                Rincian Penilaian
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Nilai berdasarkan lima aspek penilaian magang.
            </p>

            <div class="mt-6 space-y-4">

                <div class="flex items-center justify-between rounded-xl bg-gray-50 p-4">

                    <div>

                        <p class="font-semibold text-gray-900">
                            Kedisiplinan
                        </p>

                        <p class="text-xs text-gray-500">
                            Bobot 20%
                        </p>

                    </div>

                    <span class="text-2xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_kedisiplinan, 2) }}
                    </span>

                </div>

                <div class="flex items-center justify-between rounded-xl bg-gray-50 p-4">

                    <div>

                        <p class="font-semibold text-gray-900">
                            Kehadiran
                        </p>

                        <p class="text-xs text-gray-500">
                            Bobot 20%
                        </p>

                    </div>

                    <span class="text-2xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_kehadiran, 2) }}
                    </span>

                </div>

                <div class="flex items-center justify-between rounded-xl bg-gray-50 p-4">

                    <div>

                        <p class="font-semibold text-gray-900">
                            Kinerja / Tanggung Jawab
                        </p>

                        <p class="text-xs text-gray-500">
                            Bobot 20%
                        </p>

                    </div>

                    <span class="text-2xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_kinerja, 2) }}
                    </span>

                </div>

                <div class="flex items-center justify-between rounded-xl bg-gray-50 p-4">

                    <div>

                        <p class="font-semibold text-gray-900">
                            Kompetensi
                        </p>

                        <p class="text-xs text-gray-500">
                            Bobot 20%
                        </p>

                    </div>

                    <span class="text-2xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_kompetensi, 2) }}
                    </span>

                </div>

                <div class="flex items-center justify-between rounded-xl bg-gray-50 p-4">

                    <div>

                        <p class="font-semibold text-gray-900">
                            Sikap / Etika
                        </p>

                        <p class="text-xs text-gray-500">
                            Bobot 20%
                        </p>

                    </div>

                    <span class="text-2xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_sikap, 2) }}
                    </span>

                </div>

            </div>

            {{-- Nilai akhir --}}
            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-8 text-center">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    Nilai Akhir
                </p>

                <p class="mt-2 text-6xl font-bold text-emerald-800">
                    {{ number_format((float) $penilaian->nilai_akhir, 2) }}
                </p>

            </div>

        </div>

        {{-- Catatan --}}
        @if ($penilaian->catatan)

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Catatan Mentor
            </p>

            <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-700">
                {{ $penilaian->catatan }}
            </p>

        </div>

        @endif

        {{-- Final --}}
        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

            <p class="text-sm text-emerald-800">

                Penilaian difinalisasi pada
                <strong>
                    {{ $penilaian->finalized_at?->format('d-m-Y H:i') }}
                </strong>.

            </p>

        </div>

        @endif

    </div>

</x-simaga-layout>