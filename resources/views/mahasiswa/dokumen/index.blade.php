<x-simaga-layout>

    <x-slot:title>Dokumen - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Dokumen Saya</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">

            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </p>

        </div>

        @endif

        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">

            <ul class="space-y-1 text-sm text-rose-800 dark:text-rose-300">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        {{-- Header --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Administrasi Magang
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Dokumen Saya
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Simpan dan kelola dokumen administrasi magang Anda.
                </p>

            </div>

            <a
                href="{{ route('mahasiswa.dokumen.create') }}"
                class="inline-flex w-fit items-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">

                <svg
                    class="h-4 w-4"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 4v16m8-8H4" />
                </svg>

                Upload Dokumen

            </a>

        </div>

        {{-- Info penempatan --}}
        @if ($penempatan)

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Mahasiswa
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mahasiswa->user->name }}
                    </p>

                    <p class="mt-1 text-xs text-gray-500">
                        {{ $mahasiswa->nim }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Mentor
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->mentor->user->name }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Periode
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->periodeMagang->nama_periode }}
                    </p>

                </div>

            </div>

        </div>

        @endif

        {{-- Statistik --}}
        @php
        $uploadedCount = $dokumen->where('status', 'uploaded')->count();
        $verifiedCount = $dokumen->where('status', 'verified')->count();
        $revisionCount = $dokumen->where('status', 'revision')->count();
        @endphp

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Total
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $dokumen->count() }}
                </p>

            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/40 dark:bg-amber-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">
                    Diupload
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-800">
                    {{ $uploadedCount }}
                </p>

            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    Terverifikasi
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800">
                    {{ $verifiedCount }}
                </p>

            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 dark:border-rose-900/40 dark:bg-rose-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">
                    Revisi
                </p>

                <p class="mt-2 text-3xl font-bold text-rose-800">
                    {{ $revisionCount }}
                </p>

            </div>

        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Daftar Dokumen
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Dokumen yang pernah Anda unggah.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Dokumen
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Jenis
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Ukuran
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($dokumen as $item)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $item->nama_dokumen }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->nama_file }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">

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

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600 dark:text-gray-300">

                                @if ($item->ukuran_file)
                                {{ number_format($item->ukuran_file / 1024, 1) }} KB
                                @else
                                -
                                @endif

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

                            <td class="px-5 py-4">

                                <div class="flex flex-wrap justify-end gap-2">

                                    @if (
                                    in_array($item->mime_type, [
                                    'application/pdf',
                                    'image/jpeg',
                                    'image/png',
                                    'image/webp'
                                    ], true)
                                    )

                                    <a
                                        href="{{ route('mahasiswa.dokumen.preview', $item) }}"
                                        target="_blank"
                                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                        Preview
                                    </a>

                                    @endif

                                    <a
                                        href="{{ route('mahasiswa.dokumen.download', $item) }}"
                                        class="rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50">
                                        Download
                                    </a>

                                    @if ($item->status === 'revision')

                                    <a
                                        href="{{ route('mahasiswa.dokumen.create') }}"
                                        class="rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                                        Upload Ulang
                                    </a>

                                    @endif

                                    @if ($item->status !== 'verified')

                                    <form
                                        method="POST"
                                        action="{{ route('mahasiswa.dokumen.destroy', $item) }}"
                                        onsubmit="return confirm('Hapus dokumen ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-rose-600 px-3 py-2 text-xs font-semibold text-white hover:bg-rose-700">
                                            Hapus
                                        </button>

                                    </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-16 text-center">

                                <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Belum ada dokumen
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Silakan unggah dokumen yang diperlukan.
                                </p>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-simaga-layout>