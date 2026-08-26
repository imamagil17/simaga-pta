<x-simaga-layout>

    <x-slot:title>Detail Penilaian - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Penilaian</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        <div>

            <a
                href="{{ route('admin.penilaian.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Monitoring Penilaian
            </a>

        </div>

        {{-- Identitas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mahasiswa
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-900">
                        {{ $penilaian->mahasiswa->user->name ?? '-' }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $penilaian->mahasiswa->nim ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mentor
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $penilaian->mentor->user->name ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $penilaian->penempatan->periodeMagang->nama_periode ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Nilai --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex items-center justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Hasil Penilaian
                    </p>

                    <h3 class="mt-1 text-xl font-bold text-gray-900">
                        Rincian Nilai
                    </h3>

                </div>

                @if ($penilaian->status === 'final')

                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                    Final
                </span>

                @else

                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                    Draft
                </span>

                @endif

            </div>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="text-sm text-gray-500">
                        Kedisiplinan
                    </p>

                    <p class="mt-1 text-3xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_kedisiplinan, 2) }}
                    </p>

                    <p class="text-xs text-gray-400">
                        Bobot 20%
                    </p>

                </div>

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="text-sm text-gray-500">
                        Kehadiran
                    </p>

                    <p class="mt-1 text-3xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_kehadiran, 2) }}
                    </p>

                    <p class="text-xs text-gray-400">
                        Bobot 20%
                    </p>

                </div>

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="text-sm text-gray-500">
                        Kinerja / Tanggung Jawab
                    </p>

                    <p class="mt-1 text-3xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_kinerja, 2) }}
                    </p>

                    <p class="text-xs text-gray-400">
                        Bobot 20%
                    </p>

                </div>

                <div class="rounded-xl bg-gray-50 p-5">

                    <p class="text-sm text-gray-500">
                        Kompetensi
                    </p>

                    <p class="mt-1 text-3xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_kompetensi, 2) }}
                    </p>

                    <p class="text-xs text-gray-400">
                        Bobot 20%
                    </p>

                </div>

                <div class="rounded-xl bg-gray-50 p-5 sm:col-span-2">

                    <p class="text-sm text-gray-500">
                        Sikap / Etika
                    </p>

                    <p class="mt-1 text-3xl font-bold text-gray-900">
                        {{ number_format((float) $penilaian->nilai_sikap, 2) }}
                    </p>

                    <p class="text-xs text-gray-400">
                        Bobot 20%
                    </p>

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

        {{-- Finalisasi --}}
        @if ($penilaian->finalizer)

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

            <p class="text-sm text-emerald-800">

                Difinalisasi oleh
                <strong>
                    {{ $penilaian->finalizer->name }}
                </strong>

                @if ($penilaian->finalized_at)

                pada
                {{ $penilaian->finalized_at->format('d-m-Y H:i') }}

                @endif

            </p>

        </div>

        @endif

    </div>

</x-simaga-layout>