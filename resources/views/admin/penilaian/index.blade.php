<x-simaga-layout>

    <x-slot:title>Monitoring Penilaian - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Monitoring Penilaian</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    Monitoring
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Monitoring Penilaian
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Pantau hasil evaluasi mahasiswa dari seluruh mentor.
                </p>

            </div>

            <a
                href="{{ route('admin.dashboard') }}"
                class="inline-flex w-fit rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                ← Dashboard
            </a>

        </div>

        {{-- Rekap --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">

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
                    Draft
                </p>
                <p class="mt-2 text-3xl font-bold text-amber-800">
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

            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5">
                <p class="text-xs font-semibold uppercase text-blue-600">
                    Rata-rata
                </p>
                <p class="mt-2 text-3xl font-bold text-blue-800">
                    {{ number_format((float) $rekap['rata_rata'], 2) }}
                </p>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">
                <p class="text-xs font-semibold uppercase text-emerald-600">
                    Tertinggi
                </p>
                <p class="mt-2 text-3xl font-bold text-emerald-800">
                    {{ number_format((float) $rekap['tertinggi'], 2) }}
                </p>
            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5">
                <p class="text-xs font-semibold uppercase text-rose-600">
                    Terendah
                </p>
                <p class="mt-2 text-3xl font-bold text-rose-800">
                    {{ number_format((float) $rekap['terendah'], 2) }}
                </p>
            </div>

        </div>

        {{-- Filter --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <h3 class="font-bold text-gray-900">
                Filter Penilaian
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Gunakan filter untuk melihat data penilaian tertentu.
            </p>

            <form
                method="GET"
                action="{{ route('admin.penilaian.index') }}"
                class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">

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
                            value="draft"
                            @selected($filters['status']==='draft' )>
                            Draft
                        </option>

                        <option
                            value="final"
                            @selected($filters['status']==='final' )>
                            Final
                        </option>

                    </select>

                </div>

                {{-- Mentor --}}
                <div>

                    <label
                        for="mentor_id"
                        class="block text-sm font-semibold text-gray-700">
                        Mentor
                    </label>

                    <select
                        id="mentor_id"
                        name="mentor_id"
                        class="mt-2 block w-full rounded-xl border-gray-300 text-sm">

                        <option value="">
                            Semua Mentor
                        </option>

                        @foreach ($mentors as $mentor)

                        <option
                            value="{{ $mentor->id }}"
                            @selected((int) $filters['mentor_id']===$mentor->id)
                            >
                            {{ $mentor->user->name ?? '-' }}
                        </option>

                        @endforeach

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
                        href="{{ route('admin.penilaian.index') }}"
                        class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>

                </div>

            </form>

        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h3 class="font-bold text-gray-900">
                    Data Penilaian
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Menampilkan {{ $penilaian->count() }} penilaian.
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

                        @forelse ($penilaian as $item)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $item->mahasiswa->user->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $item->mahasiswa->nim ?? '-' }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                {{ $item->mentor->user->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                {{ $item->penempatan->periodeMagang->nama_periode ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-center">

                                @if ($item->nilai_akhir !== null)

                                <span class="text-lg font-bold text-gray-900">
                                    {{ number_format((float) $item->nilai_akhir, 2) }}
                                </span>

                                @else

                                <span class="text-gray-400">
                                    -
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-center">

                                @if ($item->status === 'final')

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                                    Final
                                </span>

                                @else

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                    Draft
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('admin.penilaian.show', $item) }}"
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
                                    Belum Ada Penilaian
                                </p>

                                <p class="mt-1 text-sm text-gray-500">
                                    Belum ada data penilaian yang tersedia.
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