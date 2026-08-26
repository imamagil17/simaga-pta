<x-simaga-layout>

    <x-slot:title>Detail Penilaian - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Penilaian</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">

            <p class="text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </p>

        </div>

        @endif

        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">

            <ul class="space-y-1 text-sm text-rose-800">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        <div>

            <a
                href="{{ route('mentor.penilaian.index') }}"
                class="text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Penilaian
            </a>

        </div>

        {{-- Identitas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mahasiswa
                    </p>

                    <h3 class="mt-1 text-xl font-bold text-gray-900">
                        {{ $penempatan->mahasiswa->user->name }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $penempatan->mahasiswa->nim }}
                    </p>

                    <p class="mt-2 text-sm text-gray-600">
                        {{ $penempatan->periodeMagang->nama_periode }}
                    </p>

                </div>

                @if ($penilaian?->status === 'final')

                <span class="inline-flex rounded-full bg-emerald-100 px-4 py-2 text-xs font-semibold text-emerald-800">
                    Final
                </span>

                @elseif ($penilaian)

                <span class="inline-flex rounded-full bg-amber-100 px-4 py-2 text-xs font-semibold text-amber-800">
                    Draft
                </span>

                @else

                <span class="inline-flex rounded-full bg-gray-100 px-4 py-2 text-xs font-semibold text-gray-700">
                    Belum Dinilai
                </span>

                @endif

            </div>

        </div>

        @if ($penilaian)

        {{-- Nilai --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <h3 class="text-lg font-bold text-gray-900">
                Hasil Penilaian
            </h3>

            <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">

                @php
                $aspek = [
                'Kedisiplinan' => $penilaian->nilai_kedisiplinan,
                'Kehadiran' => $penilaian->nilai_kehadiran,
                'Kinerja / Tanggung Jawab' => $penilaian->nilai_kinerja,
                'Kompetensi' => $penilaian->nilai_kompetensi,
                'Sikap / Etika' => $penilaian->nilai_sikap,
                ];
                @endphp

                @foreach ($aspek as $nama => $nilai)

                <div class="rounded-xl border border-gray-200 bg-gray-50 p-5">

                    <p class="text-sm font-medium text-gray-500">
                        {{ $nama }}
                    </p>

                    <p class="mt-1 text-3xl font-bold text-gray-900">
                        {{ number_format((float) $nilai, 2) }}
                    </p>

                </div>

                @endforeach

            </div>

            <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 p-6 text-center">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    Nilai Akhir
                </p>

                <p class="mt-2 text-5xl font-bold text-emerald-800">
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
        @if ($penilaian->status === 'draft')

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6">

            <h3 class="font-bold text-amber-900">
                Finalisasi Penilaian
            </h3>

            <p class="mt-1 text-sm text-amber-800">
                Setelah difinalisasi, penilaian tidak dapat diubah lagi.
            </p>

            <form
                method="POST"
                action="{{ route('mentor.penilaian.finalize', $penempatan->mahasiswa) }}"
                class="mt-5"
                onsubmit="return confirm('Finalisasi penilaian ini? Setelah final, nilai tidak dapat diubah.')">

                @csrf

                <button
                    type="submit"
                    class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                    Finalisasi Penilaian
                </button>

            </form>

        </div>

        <div>

            <a
                href="{{ route('mentor.penilaian.create', $penempatan->mahasiswa) }}"
                class="inline-flex rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                Edit Nilai
            </a>

        </div>

        @else

        @if ($penilaian->finalized_at)

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

            <p class="text-sm text-emerald-800">

                Penilaian difinalisasi pada
                <strong>
                    {{ $penilaian->finalized_at->format('d-m-Y H:i') }}
                </strong>

            </p>

        </div>

        @endif

        @endif

        @else

        <div class="rounded-2xl border border-gray-200 bg-white p-8 text-center shadow-sm">

            <h3 class="font-bold text-gray-900">
                Belum Ada Penilaian
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Silakan isi penilaian mahasiswa ini.
            </p>

            <a
                href="{{ route('mentor.penilaian.create', $penempatan->mahasiswa) }}"
                class="mt-5 inline-flex rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                Mulai Penilaian
            </a>

        </div>

        @endif

    </div>

</x-simaga-layout>