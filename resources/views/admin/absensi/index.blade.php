<x-simaga-layout>

    <x-slot:title>Monitoring Absensi - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Monitoring Absensi</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                Monitoring
            </p>

            <div class="mt-1 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Monitoring Absensi
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pantau seluruh absensi mahasiswa magang.
                    </p>
                </div>

                {{-- Export Excel --}}
                <a
                    href="{{ route('admin.absensi.export', request()->query()) }}"
                    class="inline-flex w-fit items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 3v12m0 0l4-4m-4 4l-4-4M5 21h14" />
                    </svg>

                    Export Excel
                </a>

                {{-- Export PDF --}}
                <a
                    href="{{ route('admin.absensi.export-pdf', request()->query()) }}"
                    class="inline-flex w-fit items-center gap-2 rounded-xl border border-rose-300 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 dark:border-rose-900/50 dark:bg-rose-900/20 dark:text-rose-300 dark:hover:bg-rose-900/40">
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 3v12m0 0l4-4m-4 4l-4-4M5 21h14" />
                    </svg>

                    Export PDF
                </a>
                
                <a
                    href="{{ route('admin.dashboard') }}"
                    class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7" />
                    </svg>

                    Dashboard
                </a>

            </div>

        </div>

        {{-- Filter --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <form
                method="GET"
                action="{{ route('admin.absensi.index') }}"
                class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">

                {{-- Periode --}}
                <div>
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Periode
                    </label>

                    <select
                        name="periode_id"
                        class="mt-2 w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">
                            Semua Periode
                        </option>

                        @foreach ($periodeMagangs as $periode)
                        <option
                            value="{{ $periode->id }}"
                            @selected($filters['periode_id']==$periode->id)
                            >
                            {{ $periode->nama_periode }}
                        </option>
                        @endforeach

                    </select>
                </div>

                {{-- Mentor --}}
                <div>
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Mentor
                    </label>

                    <select
                        name="mentor_id"
                        class="mt-2 w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">
                            Semua Mentor
                        </option>

                        @foreach ($mentors as $mentor)
                        <option
                            value="{{ $mentor->id }}"
                            @selected($filters['mentor_id']==$mentor->id)
                            >
                            {{ $mentor->user->name }}
                        </option>
                        @endforeach

                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Verifikasi
                    </label>

                    <select
                        name="status_verifikasi"
                        class="mt-2 w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="pending"
                            @selected($filters['status_verifikasi']==='pending' )>
                            Menunggu
                        </option>

                        <option
                            value="approved"
                            @selected($filters['status_verifikasi']==='approved' )>
                            Disetujui
                        </option>

                        <option
                            value="rejected"
                            @selected($filters['status_verifikasi']==='rejected' )>
                            Ditolak
                        </option>

                    </select>
                </div>

                {{-- Tanggal --}}
                <div>
                    <label class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        name="tanggal"
                        value="{{ $filters['tanggal'] }}"
                        class="mt-2 w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                </div>

                {{-- Aksi --}}
                <div class="flex items-end gap-2">

                    <button
                        type="submit"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z" />
                        </svg>

                        Filter
                    </button>

                    <a
                        href="{{ route('admin.absensi.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Reset
                    </a>

                </div>

            </form>

        </div>

        {{-- Rekap --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Total
                </p>
                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $rekap['total'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Hadir
                </p>
                <p class="mt-2 text-2xl font-bold text-emerald-800 dark:text-emerald-300">
                    {{ $rekap['hadir'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/40 dark:bg-amber-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                    Terlambat
                </p>
                <p class="mt-2 text-2xl font-bold text-amber-800 dark:text-amber-300">
                    {{ $rekap['terlambat'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-5 dark:border-orange-900/40 dark:bg-orange-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-orange-600 dark:text-orange-400">
                    Menit Terlambat
                </p>
                <p class="mt-2 text-2xl font-bold text-orange-800 dark:text-orange-300">
                    {{ $rekap['total_menit_terlambat'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-900/40 dark:bg-blue-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                    Menunggu
                </p>
                <p class="mt-2 text-2xl font-bold text-blue-800 dark:text-blue-300">
                    {{ $rekap['pending'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Disetujui
                </p>
                <p class="mt-2 text-2xl font-bold text-emerald-800 dark:text-emerald-300">
                    {{ $rekap['approved'] }}
                </p>
            </div>

        </div>

        {{-- Tabel --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Mentor
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tanggal
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Masuk
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Pulang
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Terlambat
                            </th>

                            <th class="px-4 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Verifikasi
                            </th>

                            <th class="px-4 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($absensis as $absensi)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $absensi->penempatan->mahasiswa->user->name }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $absensi->penempatan->mahasiswa->nim }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $absensi->penempatan->mentor->user->name }}
                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $absensi->tanggal->translatedFormat('d F Y') }}
                            </td>

                            <td class="px-4 py-4 text-center text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ \Illuminate\Support\Str::substr($absensi->jam_masuk ?? '--:--', 0, 5) }}
                            </td>

                            <td class="px-4 py-4 text-center text-sm font-semibold text-gray-800 dark:text-gray-200">
                                {{ \Illuminate\Support\Str::substr($absensi->jam_pulang ?? '--:--', 0, 5) }}
                            </td>

                            <td class="px-4 py-4 text-center">

                                @if ($absensi->menit_terlambat !== null)

                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    {{ $absensi->menit_terlambat }} menit
                                </span>

                                @else

                                <span class="text-sm text-gray-400 dark:text-gray-500">
                                    -
                                </span>

                                @endif

                            </td>

                            <td class="px-4 py-4 text-center">

                                @if ($absensi->status_verifikasi === 'pending')

                                <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    Menunggu
                                </span>

                                @elseif ($absensi->status_verifikasi === 'approved')

                                <span class="inline-flex rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    Disetujui
                                </span>

                                @else

                                <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                    Ditolak
                                </span>

                                @endif

                            </td>

                            <td class="px-4 py-4 text-right">

                                <a
                                    href="{{ route('admin.absensi.show', $absensi) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-16 text-center">

                                <div class="mx-auto max-w-sm">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">

                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M8 7h8m-8 4h8m-8 4h5M5 3h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                        </svg>

                                    </div>

                                    <h4 class="mt-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Tidak Ada Data
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada absensi yang sesuai dengan filter.
                                    </p>

                                </div>

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
</x-simaga-layout>