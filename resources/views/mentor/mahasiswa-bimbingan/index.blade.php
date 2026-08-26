<x-simaga-layout>

    <x-slot:title>Mahasiswa Bimbingan - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Mahasiswa Bimbingan</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Bimbingan
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                Mahasiswa Bimbingan
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Daftar mahasiswa yang berada dalam bimbingan Anda.
            </p>

        </div>

        {{-- Rekap --}}
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <p class="text-xs font-semibold uppercase text-gray-500">
                    Total Mahasiswa
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $rekap['total'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                <p class="text-xs font-semibold uppercase text-emerald-600">
                    Penilaian Final
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800">
                    {{ $rekap['sudah_dinilai'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">

                <p class="text-xs font-semibold uppercase text-blue-600">
                    Sertifikat Terbit
                </p>

                <p class="mt-2 text-3xl font-bold text-blue-800">
                    {{ $rekap['sertifikat'] }}
                </p>

            </div>

        </div>

        {{-- Daftar --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h3 class="font-bold text-gray-900">
                    Daftar Mahasiswa
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
                                Penilaian
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Sertifikat
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

                                @if ($penempatan->penilaian?->status === 'final')

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                                    Final
                                </span>

                                @elseif ($penempatan->penilaian)

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                    Draft
                                </span>

                                @else

                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                                    Belum Dinilai
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-center">

                                @if ($penempatan->sertifikat?->status === 'approved')

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                                    Terbit
                                </span>

                                @elseif ($penempatan->sertifikat?->status === 'pending')

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                    Menunggu
                                </span>

                                @elseif ($penempatan->sertifikat?->status === 'rejected')

                                <span class="inline-flex rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800">
                                    Ditolak
                                </span>

                                @else

                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                                    Belum Diajukan
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('mentor.mahasiswa-bimbingan.show', $penempatan->mahasiswa) }}"
                                    class="inline-flex rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                                    Detail
                                </a>

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