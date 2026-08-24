<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Tugas - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Tugas Mahasiswa</x-slot:headerTitle>

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

        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Bimbingan & Evaluasi
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Tugas Mahasiswa
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Buat dan kelola tugas untuk mahasiswa bimbingan Anda.
                </p>
            </div>

            <a
                href="{{ route('mentor.tugas.create') }}"
                class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>

                Buat Tugas
            </a>

        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Total
                </p>
                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $rekap['total'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Draft
                </p>
                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-gray-200">
                    {{ $rekap['draft'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Published
                </p>
                <p class="mt-2 text-3xl font-bold text-emerald-800 dark:text-emerald-300">
                    {{ $rekap['published'] }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Closed
                </p>
                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-gray-200">
                    {{ $rekap['closed'] }}
                </p>
            </div>

        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <form
                method="GET"
                action="{{ route('mentor.tugas.index') }}"
                class="flex flex-col gap-3 sm:flex-row sm:items-end">

                <div>

                    <label
                        for="status"
                        class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="mt-2 rounded-xl border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="draft"
                            @selected($statusFilter==='draft' )>
                            Draft
                        </option>

                        <option
                            value="published"
                            @selected($statusFilter==='published' )>
                            Published
                        </option>

                        <option
                            value="closed"
                            @selected($statusFilter==='closed' )>
                            Closed
                        </option>

                    </select>

                </div>

                <button
                    type="submit"
                    class="rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">
                    Filter
                </button>

                <a
                    href="{{ route('mentor.tugas.index') }}"
                    class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    Reset
                </a>

            </form>

        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Tugas
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                Deadline
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

                        @forelse ($tugas as $item)

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $item->judul }}
                                </p>

                                <p class="mt-1 max-w-sm truncate text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->deskripsi }}
                                </p>

                            </td>

                            <td class="px-5 py-4">

                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $item->penempatan->mahasiswa->user->name }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->penempatan->mahasiswa->nim }}
                                </p>

                            </td>

                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700 dark:text-gray-300">
                                {{ $item->tanggal_deadline->translatedFormat('d F Y, H:i') }}
                            </td>

                            <td class="px-5 py-4 text-center">

                                @switch($item->status)

                                @case('draft')
                                <span class="inline-flex rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    Draft
                                </span>
                                @break

                                @case('published')
                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                    Published
                                </span>
                                @break

                                @case('closed')
                                <span class="inline-flex rounded-full bg-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                    Closed
                                </span>
                                @break

                                @endswitch

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('mentor.tugas.show', $item) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="5"
                                class="px-6 py-16 text-center text-sm text-gray-500 dark:text-gray-400">
                                Belum ada tugas untuk mahasiswa bimbingan Anda.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-simaga-layout>