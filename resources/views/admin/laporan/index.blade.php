<x-simaga-layout>

    <x-slot:title>Laporan Magang - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Laporan Magang</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                Administrasi
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                Laporan Magang
            </h3>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Rekap kegiatan magang berdasarkan data SIMAGA.
            </p>

        </div>

        {{-- Filter --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="mb-5">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Filter Laporan
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Pilih mahasiswa atau periode untuk menampilkan rekap laporan.
                </p>

            </div>

            <form
                method="GET"
                action="{{ route('admin.laporan.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-3">

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
                            @selected(
                            (int) $filters['mahasiswa_id']===$mahasiswa->id
                            )
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
                            @selected(
                            (int) $filters['periode_id']===$periode->id
                            )
                            >
                            {{ $periode->nama_periode }}
                        </option>

                        @endforeach

                    </select>

                </div>

                {{-- Tombol --}}
                <div class="flex items-end gap-3">

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">
                        Tampilkan
                    </button>

                    <a
                        href="{{ route('admin.laporan.index') }}"
                        class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>

                </div>

            </form>

        </div>

        {{-- Hasil --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Rekap Laporan
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    {{ $laporan->count() }} data laporan ditemukan.
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
                                Mentor
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Absensi
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Logbook
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Tugas
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Nilai
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase text-gray-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($laporan as $item)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $item['mahasiswa']['nama'] }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $item['mahasiswa']['nim'] }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                {{ $item['mentor'] }}
                            </td>

                            <td class="px-5 py-4 text-center">

                                <p class="font-bold text-gray-900">
                                    {{ $item['absensi']['hadir'] }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    dari {{ $item['absensi']['total'] }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-center">

                                <p class="font-bold text-gray-900">
                                    {{ $item['logbook']['approved'] }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    disetujui
                                </p>

                            </td>

                            <td class="px-5 py-4 text-center">

                                <p class="font-bold text-gray-900">
                                    {{ $item['tugas']['approved'] }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    disetujui
                                </p>

                            </td>

                            <td class="px-5 py-4 text-center">

                                @if ($item['penilaian']['nilai_akhir'] !== null)

                                <span class="text-lg font-bold text-emerald-700">
                                    {{ number_format($item['penilaian']['nilai_akhir'], 2) }}
                                </span>

                                @else

                                <span class="text-sm text-gray-400">
                                    Belum ada
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('admin.laporan.show', $item['penempatan']) }}"
                                    class="inline-flex rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                                    Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-16 text-center">

                                @if (
                                request()->filled('mahasiswa_id') ||
                                request()->filled('periode_id')
                                )

                                <p class="text-sm font-semibold text-gray-900">
                                    Tidak Ada Data
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Tidak ada penempatan yang sesuai dengan filter.
                                </p>

                                @else

                                <p class="text-sm font-semibold text-gray-900">
                                    Belum Ada Laporan
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Pilih mahasiswa atau periode terlebih dahulu.
                                </p>

                                @endif

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-simaga-layout>