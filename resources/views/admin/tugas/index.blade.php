<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Monitoring Tugas - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Monitoring Tugas</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

        {{-- ============================================================
             FLASH SUCCESS
        ============================================================ --}}
        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">

            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </p>

        </div>

        @endif

        {{-- ============================================================
             ERROR
        ============================================================ --}}
        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">

            <ul class="space-y-1 text-sm text-rose-800 dark:text-rose-300">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        {{-- ============================================================
             HEADER
        ============================================================ --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Monitoring
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Monitoring Tugas
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Pantau tugas yang dibuat mentor dan pengumpulan mahasiswa.
                </p>

            </div>

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

        {{-- ============================================================
             REKAP
        ============================================================ --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4 lg:grid-cols-7">

            {{-- Total --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Total
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $rekap['total'] }}
                </p>

            </div>

            {{-- Draft --}}
            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900/40">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Draft
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-gray-200">
                    {{ $rekap['draft'] }}
                </p>

            </div>

            {{-- Published --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Published
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800 dark:text-emerald-300">
                    {{ $rekap['published'] }}
                </p>

            </div>

            {{-- Closed --}}
            <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5 shadow-sm dark:border-gray-700 dark:bg-gray-900/40">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Closed
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-800 dark:text-gray-200">
                    {{ $rekap['closed'] }}
                </p>

            </div>

            {{-- Submitted --}}
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 shadow-sm dark:border-amber-900/40 dark:bg-amber-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                    Menunggu
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-800 dark:text-amber-300">
                    {{ $rekap['submitted'] }}
                </p>

            </div>

            {{-- Reviewed --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Dinilai
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800 dark:text-emerald-300">
                    {{ $rekap['reviewed'] }}
                </p>

            </div>

            {{-- Revision --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 shadow-sm dark:border-rose-900/40 dark:bg-rose-900/20">

                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 dark:text-rose-400">
                    Revisi
                </p>

                <p class="mt-2 text-3xl font-bold text-rose-800 dark:text-rose-300">
                    {{ $rekap['revision'] }}
                </p>

            </div>

        </div>

        {{-- ============================================================
             FILTER
        ============================================================ --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="mb-5">

                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Filter Data
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Filter tugas berdasarkan status, mentor, atau periode.
                </p>

            </div>

            <form
                method="GET"
                action="{{ route('admin.tugas.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-3">

                {{-- Status --}}
                <div>

                    <label
                        for="status"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Status Tugas
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="mt-2 block w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="draft"
                            @selected($filters['status']==='draft' )>
                            Draft
                        </option>

                        <option
                            value="published"
                            @selected($filters['status']==='published' )>
                            Published
                        </option>

                        <option
                            value="closed"
                            @selected($filters['status']==='closed' )>
                            Closed
                        </option>

                    </select>

                </div>

                {{-- Periode --}}
                <div>

                    <label
                        for="periode_id"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Periode Magang
                    </label>

                    <select
                        id="periode_id"
                        name="periode_id"
                        class="mt-2 block w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

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

                {{-- Mentor --}}
                <div>

                    <label
                        for="mentor_id"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Mentor
                    </label>

                    <select
                        id="mentor_id"
                        name="mentor_id"
                        class="mt-2 block w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

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

                {{-- Button --}}
                <div class="flex flex-wrap gap-3 md:col-span-3">

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800">
                        Filter
                    </button>

                    <a
                        href="{{ route('admin.tugas.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        Reset
                    </a>

                </div>

            </form>

        </div>

        {{-- ============================================================
             TABLE
        ============================================================ --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Data Tugas
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Menampilkan {{ $tugas->count() }} tugas.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tugas
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Mentor
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Deadline
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Tugas
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Pengumpulan
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($tugas as $item)

                        @php
                        $submittedCount = $item->pengumpulan
                        ->where('status', 'submitted')
                        ->count();

                        $reviewedCount = $item->pengumpulan
                        ->where('status', 'reviewed')
                        ->count();

                        $revisionCount = $item->pengumpulan
                        ->where('status', 'revision')
                        ->count();
                        @endphp

                        <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                            {{-- Tugas --}}
                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $item->judul }}
                                </p>

                                <p class="mt-1 max-w-xs truncate text-sm text-gray-500 dark:text-gray-400">
                                    {{ $item->deskripsi }}
                                </p>

                            </td>

                            {{-- Mahasiswa --}}
                            <td class="px-5 py-4">

                                <p class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ $item->penempatan->mahasiswa->user->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    {{ $item->penempatan->mahasiswa->nim ?? '-' }}
                                </p>

                            </td>

                            {{-- Mentor --}}
                            <td class="px-5 py-4">

                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $item->penempatan->mentor->user->name ?? '-' }}
                                </p>

                            </td>

                            {{-- Deadline --}}
                            <td class="whitespace-nowrap px-5 py-4 text-sm text-gray-700 dark:text-gray-300">

                                {{ $item->tanggal_deadline->translatedFormat('d F Y, H:i') }}

                            </td>

                            {{-- Status Tugas --}}
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

                            {{-- Pengumpulan --}}
                            <td class="px-5 py-4 text-center">

                                <div class="space-y-1 text-xs">

                                    @if ($submittedCount > 0)

                                    <div class="font-semibold text-amber-700 dark:text-amber-300">
                                        {{ $submittedCount }} menunggu
                                    </div>

                                    @endif

                                    @if ($reviewedCount > 0)

                                    <div class="font-semibold text-emerald-700 dark:text-emerald-300">
                                        {{ $reviewedCount }} dinilai
                                    </div>

                                    @endif

                                    @if ($revisionCount > 0)

                                    <div class="font-semibold text-rose-700 dark:text-rose-300">
                                        {{ $revisionCount }} revisi
                                    </div>

                                    @endif

                                    @if (
                                    $submittedCount === 0 &&
                                    $reviewedCount === 0 &&
                                    $revisionCount === 0
                                    )

                                    <span class="text-gray-400">
                                        Belum ada
                                    </span>

                                    @endif

                                </div>

                            </td>

                            {{-- Detail --}}
                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('admin.tugas.show', $item) }}"
                                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">

                                    Detail

                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-6 py-16 text-center">

                                <div class="mx-auto max-w-sm">

                                    <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400">

                                        <svg
                                            class="h-7 w-7"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="1.8"
                                                d="M8 7h8M8 11h8M8 15h5M5 3h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                                        </svg>

                                    </div>

                                    <h4 class="mt-4 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                        Belum Ada Data
                                    </h4>

                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Tidak ada tugas yang sesuai dengan filter.
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