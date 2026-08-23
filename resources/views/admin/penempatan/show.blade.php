<x-simaga-layout>
    <x-slot:title>Detail Penempatan - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Penempatan</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Detail Penempatan
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Informasi penempatan mahasiswa pada periode magang.
                    </p>
                </div>

                <div class="flex items-center gap-2">

                    <a
                        href="{{ route('admin.penempatans.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 19l-7-7 7-7"
                            />
                        </svg>

                        Kembali
                    </a>

                    <a
                        href="{{ route('admin.penempatans.edit', $penempatan) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
                    >
                        Edit
                    </a>

                </div>

            </div>

        </div>

        {{-- Mahasiswa --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex items-center gap-4">

                @if ($penempatan->mahasiswa->foto)

                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($penempatan->mahasiswa->foto) }}"
                        alt="Foto {{ $penempatan->mahasiswa->user->name }}"
                        class="h-16 w-16 rounded-full object-cover shadow-sm ring-2 ring-emerald-700/20"
                    >

                @else

                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-emerald-800 text-lg font-bold text-amber-300">
                        {{ strtoupper(substr($penempatan->mahasiswa->user->name, 0, 2)) }}
                    </div>

                @endif

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Mahasiswa
                    </p>

                    <h4 class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->mahasiswa->user->name }}
                    </h4>

                    <p class="mt-1 font-mono text-sm text-gray-500 dark:text-gray-400">
                        {{ $penempatan->mahasiswa->nim }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Informasi --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Periode Magang
                </p>

                <h4 class="mt-2 text-base font-bold text-gray-900 dark:text-gray-100">
                    {{ $penempatan->periodeMagang->nama_periode }}
                </h4>

                <p class="mt-1 font-mono text-xs text-gray-500 dark:text-gray-400">
                    {{ $penempatan->periodeMagang->kode_periode }}
                </p>

            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Mentor Pembimbing
                </p>

                <h4 class="mt-2 text-base font-bold text-gray-900 dark:text-gray-100">
                    {{ $penempatan->mentor->user->name }}
                </h4>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    {{ $penempatan->mentor->jabatan ?: '-' }}
                </p>

            </div>

        </div>

        {{-- Status --}}
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Status Penempatan
                </p>

                <div class="mt-3">

                    @if ($penempatan->status === 'active')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>

                    @else

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                            Tidak Aktif
                        </span>

                    @endif

                </div>

            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Masa Penempatan
                </p>

                <p class="mt-3 text-sm font-semibold text-gray-900 dark:text-gray-100">
                    {{ $penempatan->tanggal_mulai?->format('d M Y') ?? '-' }}
                    —
                    {{ $penempatan->tanggal_selesai?->format('d M Y') ?? '-' }}
                </p>

            </div>

        </div>

        {{-- Keterangan --}}
        @if ($penempatan->keterangan)

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Keterangan
                </h4>

                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600 dark:text-gray-300">
                    {{ $penempatan->keterangan }}
                </p>

            </div>

        @endif

    </div>
</x-simaga-layout>