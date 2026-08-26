<x-simaga-layout>

    <x-slot:title>Dokumen Mahasiswa - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Dokumen Mahasiswa</x-slot:headerTitle>

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
                Bimbingan & Administrasi
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                Dokumen Mahasiswa
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Periksa dokumen mahasiswa yang berada dalam bimbingan Anda.
            </p>

        </div>

        {{-- Rekap --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            <div class="rounded-2xl border bg-white p-5 shadow-sm">

                <p class="text-xs font-semibold uppercase text-gray-500">
                    Total
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $rekap['total'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

                <p class="text-xs font-semibold uppercase text-amber-600">
                    Menunggu
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-800">
                    {{ $rekap['uploaded'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                <p class="text-xs font-semibold uppercase text-emerald-600">
                    Terverifikasi
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800">
                    {{ $rekap['verified'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5">

                <p class="text-xs font-semibold uppercase text-rose-600">
                    Revisi
                </p>

                <p class="mt-2 text-3xl font-bold text-rose-800">
                    {{ $rekap['revision'] }}
                </p>

            </div>

        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Daftar Dokumen
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
                                Dokumen
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Jenis
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

                        @forelse ($dokumen as $item)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $item->mahasiswa->user->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $item->mahasiswa->nim ?? '-' }}
                                </p>

                            </td>

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $item->nama_dokumen }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $item->nama_file }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">

                                @switch($item->jenis_dokumen)

                                @case('ktm')
                                KTM
                                @break

                                @case('ktp')
                                KTP
                                @break

                                @case('cv')
                                CV
                                @break

                                @case('surat_pengantar')
                                Surat Pengantar
                                @break

                                @case('surat_pernyataan')
                                Surat Pernyataan
                                @break

                                @case('surat_penempatan')
                                Surat Penempatan
                                @break

                                @case('surat_selesai_magang')
                                Surat Selesai Magang
                                @break

                                @case('laporan_magang')
                                Laporan Magang
                                @break

                                @case('lampiran_laporan')
                                Lampiran Laporan
                                @break

                                @case('dokumen_pendukung')
                                Dokumen Pendukung
                                @break

                                @default
                                Dokumen Lainnya

                                @endswitch

                            </td>

                            <td class="px-5 py-4 text-center">

                                @switch($item->status)

                                @case('uploaded')

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                    Menunggu Verifikasi
                                </span>

                                @break

                                @case('verified')

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                                    Terverifikasi
                                </span>

                                @break

                                @case('revision')

                                <span class="inline-flex rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800">
                                    Perlu Revisi
                                </span>

                                @break

                                @endswitch

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('mentor.dokumen.show', $item) }}"
                                    class="inline-flex items-center rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                                    Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-16 text-center text-sm text-gray-500">
                                Belum ada dokumen mahasiswa yang perlu ditampilkan.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-simaga-layout>