<x-simaga-layout>

    <x-slot:title>Penilaian Mahasiswa - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Penilaian Mahasiswa</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

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

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Bimbingan & Evaluasi
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                Penilaian Mahasiswa
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Kelola penilaian mahasiswa yang berada dalam bimbingan Anda.
            </p>

        </div>

        {{-- Rekap --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <p class="text-xs font-semibold uppercase text-gray-500">
                    Total
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $rekap['total'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

                <p class="text-xs font-semibold uppercase text-amber-600">
                    Belum Dinilai
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-800">
                    {{ $rekap['belum_dinilai'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">

                <p class="text-xs font-semibold uppercase text-gray-500">
                    Draft
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-800">
                    {{ $rekap['draft'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                <p class="text-xs font-semibold uppercase text-emerald-600">
                    Final
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800">
                    {{ $rekap['final'] }}
                </p>

            </div>

        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h3 class="font-bold text-gray-900">
                    Mahasiswa Bimbingan
                </h3>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Periode
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Nilai Akhir
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase text-gray-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($penempatans as $penempatan)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $penempatan->mahasiswa->user->name }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $penempatan->mahasiswa->nim }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">

                                {{ $penempatan->periodeMagang->nama_periode }}

                            </td>

                            <td class="px-5 py-4 text-center">

                                @if ($penempatan->penilaian)

                                <span class="text-lg font-bold text-gray-900">
                                    {{ number_format((float) $penempatan->penilaian->nilai_akhir, 2) }}
                                </span>

                                @else

                                <span class="text-gray-400">
                                    -
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-center">

                                @if (! $penempatan->penilaian)

                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                                    Belum Dinilai
                                </span>

                                @elseif ($penempatan->penilaian->status === 'draft')

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                    Draft
                                </span>

                                @else

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                                    Final
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-right">

                                @if ($penempatan->penilaian)

                                <a
                                    href="{{ route('mentor.penilaian.show', $penempatan->mahasiswa) }}"
                                    class="inline-flex rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                    Detail
                                </a>

                                @else

                                <a
                                    href="{{ route('mentor.penilaian.create', $penempatan->mahasiswa) }}"
                                    class="inline-flex rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                                    Nilai
                                </a>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-16 text-center text-sm text-gray-500">
                                Belum ada mahasiswa bimbingan aktif.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-simaga-layout>