<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Monitoring Dokumen - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Monitoring Dokumen</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Monitoring
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Monitoring Dokumen
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Pantau seluruh dokumen mahasiswa selama kegiatan magang.
                </p>

            </div>

            <a
                href="{{ route('admin.dashboard') }}"
                class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                ← Dashboard
            </a>

        </div>

        {{-- Rekap --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Total Dokumen
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $rekap['total'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600">
                    Menunggu
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-800">
                    {{ $rekap['uploaded'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    Terverifikasi
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800">
                    {{ $rekap['verified'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">
                    Revisi
                </p>

                <p class="mt-2 text-3xl font-bold text-rose-800">
                    {{ $rekap['revision'] }}
                </p>

            </div>

        </div>

        {{-- Filter --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="mb-5">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Filter Dokumen
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Saring data berdasarkan status, jenis, mahasiswa, atau periode.
                </p>

            </div>

            <form
                method="GET"
                action="{{ route('admin.dokumen.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

                {{-- Status --}}
                <div>

                    <label
                        for="status"
                        class="block text-sm font-semibold text-gray-700">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="mt-2 block w-full rounded-xl border-gray-300 text-sm">

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="uploaded"
                            @selected($filters['status']==='uploaded' )>
                            Menunggu Verifikasi
                        </option>

                        <option
                            value="verified"
                            @selected($filters['status']==='verified' )>
                            Terverifikasi
                        </option>

                        <option
                            value="revision"
                            @selected($filters['status']==='revision' )>
                            Perlu Revisi
                        </option>

                    </select>

                </div>

                {{-- Jenis --}}
                <div>

                    <label
                        for="jenis_dokumen"
                        class="block text-sm font-semibold text-gray-700">
                        Jenis Dokumen
                    </label>

                    <select
                        id="jenis_dokumen"
                        name="jenis_dokumen"
                        class="mt-2 block w-full rounded-xl border-gray-300 text-sm">

                        <option value="">
                            Semua Jenis
                        </option>

                        <option value="ktm" @selected($filters['jenis_dokumen']==='ktm' )>
                            KTM
                        </option>

                        <option value="ktp" @selected($filters['jenis_dokumen']==='ktp' )>
                            KTP
                        </option>

                        <option value="cv" @selected($filters['jenis_dokumen']==='cv' )>
                            CV
                        </option>

                        <option value="surat_pengantar" @selected($filters['jenis_dokumen']==='surat_pengantar' )>
                            Surat Pengantar
                        </option>

                        <option value="surat_pernyataan" @selected($filters['jenis_dokumen']==='surat_pernyataan' )>
                            Surat Pernyataan
                        </option>

                        <option value="surat_penempatan" @selected($filters['jenis_dokumen']==='surat_penempatan' )>
                            Surat Penempatan
                        </option>

                        <option value="surat_selesai_magang" @selected($filters['jenis_dokumen']==='surat_selesai_magang' )>
                            Surat Selesai Magang
                        </option>

                        <option value="laporan_magang" @selected($filters['jenis_dokumen']==='laporan_magang' )>
                            Laporan Magang
                        </option>

                        <option value="lampiran_laporan" @selected($filters['jenis_dokumen']==='lampiran_laporan' )>
                            Lampiran Laporan
                        </option>

                        <option value="dokumen_pendukung" @selected($filters['jenis_dokumen']==='dokumen_pendukung' )>
                            Dokumen Pendukung
                        </option>

                        <option value="dokumen_lainnya" @selected($filters['jenis_dokumen']==='dokumen_lainnya' )>
                            Dokumen Lainnya
                        </option>

                    </select>

                </div>

                {{-- Mahasiswa --}}
                <div>

                    <label
                        for="mahasiswa_id"
                        class="block text-sm font-semibold text-gray-700">
                        Mahasiswa
                    </label>

                    <select
                        id="mahasiswa_id"
                        name="mahasiswa_id"
                        class="mt-2 block w-full rounded-xl border-gray-300 text-sm">

                        <option value="">
                            Semua Mahasiswa
                        </option>

                        @foreach ($mahasiswas as $mahasiswa)

                        <option
                            value="{{ $mahasiswa->id }}"
                            @selected((int) $filters['mahasiswa_id']===$mahasiswa->id)
                            >
                            {{ $mahasiswa->user->name ?? '-' }}
                            — {{ $mahasiswa->nim }}
                        </option>

                        @endforeach

                    </select>

                </div>

                {{-- Periode --}}
                <div>

                    <label
                        for="periode_id"
                        class="block text-sm font-semibold text-gray-700">
                        Periode
                    </label>

                    <select
                        id="periode_id"
                        name="periode_id"
                        class="mt-2 block w-full rounded-xl border-gray-300 text-sm">

                        <option value="">
                            Semua Periode
                        </option>

                        @foreach ($periodeMagangs as $periode)

                        <option
                            value="{{ $periode->id }}"
                            @selected((int) $filters['periode_id']===$periode->id)
                            >
                            {{ $periode->nama_periode }}
                        </option>

                        @endforeach

                    </select>

                </div>

                <div class="flex gap-3 lg:col-span-4">

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">
                        Filter
                    </button>

                    <a
                        href="{{ route('admin.dokumen.index') }}"
                        class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>

                </div>

            </form>

        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Data Dokumen
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Menampilkan {{ $dokumen->count() }} dokumen.
                </p>

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
                                Mentor
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Diunggah
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

                                {{ $item->penempatan->mentor->user->name ?? '-' }}

                            </td>

                            <td class="px-5 py-4 text-center">

                                @switch($item->status)

                                @case('uploaded')

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                    Menunggu
                                </span>

                                @break

                                @case('verified')

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                                    Terverifikasi
                                </span>

                                @break

                                @case('revision')

                                <span class="inline-flex rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800">
                                    Revisi
                                </span>

                                @break

                                @endswitch

                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-600">

                                {{ $item->created_at?->translatedFormat('d F Y, H:i') }}

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('admin.dokumen.show', $item) }}"
                                    class="inline-flex rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                                    Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-16 text-center">

                                <p class="text-sm font-semibold text-gray-900">
                                    Belum Ada Dokumen
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Tidak ada dokumen yang sesuai dengan filter.
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