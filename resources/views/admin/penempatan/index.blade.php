<x-simaga-layout>
    <x-slot:title>Data Penempatan - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Data Penempatan</x-slot:headerTitle>

    <div class="space-y-6">

        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                <div class="flex items-center gap-2">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7"
                        />
                    </svg>

                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>
                <h3 class="flex items-center gap-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                    <svg class="h-6 w-6 text-emerald-700 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 7h8m-8 4h8m-8 4h5M5 3h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"
                        />
                    </svg>

                    Data Penempatan
                </h3>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                    Kelola penempatan mahasiswa pada periode dan mentor masing-masing.
                </p>
            </div>

            <div class="flex items-center gap-2">

                <span class="inline-flex items-center rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:border-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                    Total: {{ $penempatans->count() }}
                </span>

                <a
                    href="{{ route('admin.penempatans.create') }}"
                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4v16m8-8H4"
                        />
                    </svg>

                    Tambah Penempatan
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
                                Mahasiswa
                            </th>

                            <th class="px-4 py-3.5">
                                Periode
                            </th>

                            <th class="px-4 py-3.5">
                                Mentor
                            </th>

                            <th class="px-4 py-3.5">
                                Masa Penempatan
                            </th>

                            <th class="px-4 py-3.5 text-center">
                                Status
                            </th>

                            <th class="px-4 py-3.5 text-center">
                                Aksi
                            </th>

                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700/60">

                        @forelse ($penempatans as $index => $penempatan)

                            <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-gray-700/30">

                                <td class="px-4 py-4 text-center font-medium text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Mahasiswa --}}
                                <td class="px-4 py-4">

                                    <div class="flex items-center gap-3">

                                        @if ($penempatan->mahasiswa->foto)

                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::url($penempatan->mahasiswa->foto) }}"
                                                alt="Foto {{ $penempatan->mahasiswa->user->name }}"
                                                class="h-10 w-10 rounded-full object-cover ring-2 ring-emerald-700/20"
                                            >

                                        @else

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-800 text-xs font-bold text-amber-300">
                                                {{ strtoupper(substr($penempatan->mahasiswa->user->name, 0, 2)) }}
                                            </div>

                                        @endif

                                        <div class="min-w-0">
                                            <div class="truncate font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $penempatan->mahasiswa->user->name }}
                                            </div>

                                            <div class="truncate text-xs text-gray-500 dark:text-gray-400">
                                                {{ $penempatan->mahasiswa->nim }}
                                            </div>
                                        </div>

                                    </div>

                                </td>

                                {{-- Periode --}}
                                <td class="px-4 py-4">

                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $penempatan->periodeMagang->nama_periode }}
                                    </div>

                                    <div class="mt-1 font-mono text-xs text-gray-500 dark:text-gray-400">
                                        {{ $penempatan->periodeMagang->kode_periode }}
                                    </div>

                                </td>

                                {{-- Mentor --}}
                                <td class="px-4 py-4">

                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $penempatan->mentor->user->name }}
                                    </div>

                                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $penempatan->mentor->jabatan ?: '-' }}
                                    </div>

                                </td>

                                {{-- Masa --}}
                                <td class="whitespace-nowrap px-4 py-4 text-xs text-gray-600 dark:text-gray-300">

                                    @if ($penempatan->tanggal_mulai || $penempatan->tanggal_selesai)

                                        {{ $penempatan->tanggal_mulai?->format('d M Y') ?? '-' }}
                                        —
                                        {{ $penempatan->tanggal_selesai?->format('d M Y') ?? '-' }}

                                    @else

                                        <span class="italic text-gray-400">
                                            Mengikuti periode
                                        </span>

                                    @endif

                                </td>

                                {{-- Status --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">

                                    @if ($penempatan->status === 'active')

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                            Aktif
                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                            Tidak Aktif
                                        </span>

                                    @endif

                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-4">

                                    <div class="flex items-center justify-center gap-2">

                                        <a
                                            href="{{ route('admin.penempatans.show', $penempatan) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300"
                                        >
                                            Detail
                                        </a>

                                        <a
                                            href="{{ route('admin.penempatans.edit', $penempatan) }}"
                                            class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                        >
                                            Edit
                                        </a>

                                        <form
                                            method="POST"
                                            action="{{ route('admin.penempatans.toggle-status', $penempatan) }}"
                                        >
                                            @csrf
                                            @method('PUT')

                                            @if ($penempatan->status === 'active')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100 dark:border-rose-800 dark:bg-rose-900/20 dark:text-rose-300"
                                                >
                                                    Nonaktifkan
                                                </button>

                                            @else

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300"
                                                >
                                                    Aktifkan
                                                </button>

                                            @endif

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-6 py-16 text-center">

                                    <div class="mx-auto flex max-w-sm flex-col items-center">

                                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M8 7h8m-8 4h8m-8 4h5M5 3h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"
                                                />
                                            </svg>
                                        </div>

                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Belum Ada Penempatan
                                        </h4>

                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Belum ada mahasiswa yang ditempatkan pada periode magang.
                                        </p>

                                        <a
                                            href="{{ route('admin.penempatans.create') }}"
                                            class="mt-4 inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-xs font-semibold text-white hover:bg-emerald-800"
                                        >
                                            Tambah Penempatan
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