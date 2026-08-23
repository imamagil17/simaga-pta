<x-simaga-layout>
    <x-slot:title>Periode Magang - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Periode Magang</x-slot:headerTitle>

    <div class="space-y-6">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h3 class="flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                    <svg
                        class="h-6 w-6 text-emerald-700 dark:text-emerald-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"
                        />
                    </svg>

                    Periode Magang
                </h3>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                    Kelola periode pelaksanaan magang di SIMAGA PTA.
                </p>
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">

                <span class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:border-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                    Total: {{ $periodes->count() }} Periode
                </span>

                <a
                    href="{{ route('admin.periode-magangs.create') }}"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-emerald-700 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-emerald-800"
                >
                    <svg
                        class="h-4 w-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    Tambah Periode
                </a>

            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">

                    <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wider text-gray-700 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-200">
                        <tr>
                            <th class="w-12 px-4 py-3.5 text-center">
                                No
                            </th>

                            <th class="px-4 py-3.5">
                                Nama Periode
                            </th>

                            <th class="px-4 py-3.5">
                                Kode Periode
                            </th>

                            <th class="px-4 py-3.5">
                                Tanggal Mulai
                            </th>

                            <th class="px-4 py-3.5">
                                Tanggal Selesai
                            </th>

                            <th class="px-4 py-3.5 text-center">
                                Status
                            </th>

                            <th class="px-4 py-3.5">
                                Keterangan
                            </th>

                            <th class="px-4 py-3.5 text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700/60">

                        @forelse ($periodes as $index => $periode)

                            <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-gray-700/30">

                                {{-- No --}}
                                <td class="px-4 py-4 text-center font-medium text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Nama Periode --}}
                                <td class="px-4 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $periode->nama_periode }}
                                    </div>
                                </td>

                                {{-- Kode Periode --}}
                                <td class="whitespace-nowrap px-4 py-4">
                                    <span class="rounded-lg bg-gray-100 px-2.5 py-1 font-mono text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                        {{ $periode->kode_periode }}
                                    </span>
                                </td>

                                {{-- Tanggal Mulai --}}
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $periode->tanggal_mulai?->format('d M Y') ?? '-' }}
                                </td>

                                {{-- Tanggal Selesai --}}
                                <td class="whitespace-nowrap px-4 py-4 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $periode->tanggal_selesai?->format('d M Y') ?? '-' }}
                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">

                                    @if ($periode->status === 'active')

                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Tidak Aktif
                                        </span>

                                    @endif

                                </td>

                                {{-- Keterangan --}}
                                <td class="max-w-xs px-4 py-4">
                                    <div class="truncate text-sm text-gray-600 dark:text-gray-300">
                                        {{ $periode->keterangan ?: '-' }}
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">

                                    <div class="flex items-center justify-center gap-2">

                                        {{-- Kelola Mentor --}}
                                        <a
                                            href="{{ route('admin.periode-magangs.mentors.index', $periode) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/40"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M17 20h5v-1a5 5 0 00-5-5m-9 6H3v-1a5 5 0 0110 0v1m-2-8a4 4 0 11-8 0 4 4 0 018 0zm11 1a3 3 0 10-6 0"
                                                />
                                            </svg>

                                            Mentor
                                        </a>

                                        {{-- Edit Periode --}}
                                        <a
                                            href="{{ route('admin.periode-magangs.edit', $periode) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M11 5h2m-1-1v2m7.071 3.071l1.414 1.414M19 13v2m-2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h5"
                                                />
                                            </svg>

                                            Edit
                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td
                                    colspan="8"
                                    class="px-6 py-16 text-center text-gray-500 dark:text-gray-400"
                                >

                                    <div class="mx-auto flex max-w-sm flex-col items-center">

                                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <svg
                                                class="h-7 w-7"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5v12a2 2 0 002 2z"
                                                />
                                            </svg>
                                        </div>

                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Belum Ada Periode Magang
                                        </h4>

                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Belum terdapat periode magang yang terdaftar pada sistem.
                                        </p>

                                        <a
                                            href="{{ route('admin.periode-magangs.create') }}"
                                            class="mt-4 inline-flex items-center gap-2 rounded-lg bg-emerald-700 px-4 py-2.5 text-xs font-semibold text-white transition hover:bg-emerald-800"
                                        >
                                            <svg
                                                class="h-4 w-4"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 4v16m8-8H4"
                                                />
                                            </svg>

                                            Tambah Periode Pertama
                                        </a>

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