<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Logbook Mahasiswa - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Logbook Mahasiswa</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- Flash --}}
        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">
            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </p>
        </div>

        @endif

        @if ($errors->has('logbook'))

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">
            <p class="text-sm font-semibold text-rose-800 dark:text-rose-300">
                {{ $errors->first('logbook') }}
            </p>
        </div>

        @endif

        {{-- Header --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Bimbingan & Evaluasi
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Logbook Mahasiswa
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Periksa kegiatan mahasiswa yang berada dalam bimbingan Anda.
                </p>

            </div>

            <a
                href="{{ route('mentor.dashboard') }}"
                class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                Dashboard
            </a>

        </div>

        {{-- Rekap --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Total
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $rekap['total'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/40 dark:bg-amber-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                    Menunggu
                </p>

                <p class="mt-2 text-2xl font-bold text-amber-800 dark:text-amber-300">
                    {{ $rekap['submitted'] }}
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

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 dark:border-rose-900/40 dark:bg-rose-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-400">
                    Revisi
                </p>

                <p class="mt-2 text-2xl font-bold text-rose-800 dark:text-rose-300">
                    {{ $rekap['revision'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Draft
                </p>

                <p class="mt-2 text-2xl font-bold text-gray-800 dark:text-gray-200">
                    {{ $rekap['draft'] }}
                </p>
            </div>

        </div>

        {{-- Filter --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <form
                method="GET"
                action="{{ route('mentor.logbook.index') }}"
                class="flex flex-col gap-3 sm:flex-row sm:items-end">

                <div class="w-full sm:max-w-xs">

                    <label
                        for="status"
                        class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="mt-2 block w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">
                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="submitted"
                            @selected($statusFilter==='submitted' )>
                            Menunggu
                        </option>

                        <option
                            value="approved"
                            @selected($statusFilter==='approved' )>
                            Disetujui
                        </option>

                        <option
                            value="revision"
                            @selected($statusFilter==='revision' )>
                            Revisi
                        </option>

                        <option
                            value="draft"
                            @selected($statusFilter==='draft' )>
                            Draft
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                    Filter
                </button>

                <a
                    href="{{ route('mentor.logbook.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                    Reset
                </a>

            </form>

        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tanggal
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Kegiatan
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Status
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($logbooks as $logbook)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $logbook->penempatan->mahasiswa->user->name }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $logbook->penempatan->mahasiswa->nim }}
                                </p>

                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $logbook->tanggal->translatedFormat('d F Y') }}
                            </td>

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $logbook->judul_kegiatan }}
                                </p>

                                <p class="mt-1 max-w-md truncate text-sm text-gray-500 dark:text-gray-400">
                                    {{ $logbook->uraian_kegiatan }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-center">

                                @switch($logbook->status)

                                @case('submitted')
                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                                    Menunggu
                                </span>
                                @break

                                @case('approved')
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    Disetujui
                                </span>
                                @break

                                @case('revision')
                                <span class="inline-flex rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                                    Revisi
                                </span>
                                @break

                                @default
                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    Draft
                                </span>

                                @endswitch

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('mentor.logbook.show', $logbook) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                                    Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-16 text-center">

                                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Belum Ada Logbook
                                </h4>

                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Belum ada logbook mahasiswa bimbingan yang sesuai filter.
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