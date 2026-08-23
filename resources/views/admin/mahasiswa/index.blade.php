<x-simaga-layout>
    <x-slot:title>Data Mahasiswa - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Data Mahasiswa</x-slot:headerTitle>

    <div class="space-y-6">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                <div class="flex items-center gap-2">

                    <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                            d="M12 4.354a4 4 0 100 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                        />
                    </svg>

                    Data Mahasiswa

                </h3>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                    Daftar mahasiswa yang terdaftar dalam SIMAGA PTA.
                </p>

            </div>

            <div class="flex items-center gap-2">

                <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:border-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                    Total: {{ $mahasiswas->count() }} Mahasiswa
                </span>

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
                                NIM
                            </th>

                            <th class="px-4 py-3.5">
                                Perguruan Tinggi
                            </th>

                            <th class="px-4 py-3.5">
                                Program Studi
                            </th>

                            <th class="px-4 py-3.5">
                                Jenis Kelamin
                            </th>

                            <th class="px-4 py-3.5">
                                No. HP
                            </th>

                            <th class="px-4 py-3.5 text-center">
                                Status Akun
                            </th>

                            <th class="px-4 py-3.5 text-center">
                                Status Profil
                            </th>

                            <th class="px-4 py-3.5 text-center">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700/60">

                        @forelse ($mahasiswas as $index => $mahasiswaUser)

                            <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-gray-700/30">

                                {{-- No --}}
                                <td class="px-4 py-4 text-center font-medium text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Mahasiswa --}}
                                <td class="px-4 py-4">

                                    <div class="flex items-center gap-3">

                                        @if ($mahasiswaUser->mahasiswa?->foto)

                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::url($mahasiswaUser->mahasiswa->foto) }}"
                                                alt="Foto {{ $mahasiswaUser->name }}"
                                                class="h-10 w-10 shrink-0 rounded-full object-cover shadow-sm ring-2 ring-emerald-700/30"
                                            >

                                        @else

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-800 text-xs font-bold text-amber-300 shadow-sm ring-1 ring-emerald-600/30">
                                                {{ strtoupper(substr($mahasiswaUser->name, 0, 2)) }}
                                            </div>

                                        @endif

                                        <div class="min-w-0">

                                            <div class="truncate font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $mahasiswaUser->name }}
                                            </div>

                                            <div class="truncate text-xs text-gray-500 dark:text-gray-400">
                                                {{ $mahasiswaUser->email }}
                                            </div>

                                        </div>

                                    </div>

                                </td>

                                {{-- NIM --}}
                                <td class="whitespace-nowrap px-4 py-4">

                                    @if ($mahasiswaUser->mahasiswa?->nim)

                                        <span class="font-mono text-xs text-gray-800 dark:text-gray-200">
                                            {{ $mahasiswaUser->mahasiswa->nim }}
                                        </span>

                                    @else

                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>

                                    @endif

                                </td>

                                {{-- Perguruan Tinggi --}}
                                <td class="px-4 py-4">

                                    @if ($mahasiswaUser->mahasiswa?->perguruan_tinggi)

                                        <span class="text-xs text-gray-800 dark:text-gray-200">
                                            {{ $mahasiswaUser->mahasiswa->perguruan_tinggi }}
                                        </span>

                                    @else

                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>

                                    @endif

                                </td>

                                {{-- Program Studi --}}
                                <td class="px-4 py-4">

                                    @if ($mahasiswaUser->mahasiswa?->program_studi)

                                        <span class="text-xs text-gray-800 dark:text-gray-200">
                                            {{ $mahasiswaUser->mahasiswa->program_studi }}
                                        </span>

                                    @else

                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>

                                    @endif

                                </td>

                                {{-- Jenis Kelamin --}}
                                <td class="whitespace-nowrap px-4 py-4">

                                    @if ($mahasiswaUser->mahasiswa?->jenis_kelamin)

                                        <span class="text-xs text-gray-800 dark:text-gray-200">
                                            {{ $mahasiswaUser->mahasiswa->jenis_kelamin }}
                                        </span>

                                    @else

                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>

                                    @endif

                                </td>

                                {{-- No HP --}}
                                <td class="whitespace-nowrap px-4 py-4">

                                    @if ($mahasiswaUser->mahasiswa?->no_hp)

                                        <span class="font-mono text-xs text-gray-800 dark:text-gray-200">
                                            {{ $mahasiswaUser->mahasiswa->no_hp }}
                                        </span>

                                    @else

                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>

                                    @endif

                                </td>

                                {{-- Status Akun --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">

                                    @if ($mahasiswaUser->status === 'active')

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                            Aktif
                                        </span>

                                    @else

                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Tidak Aktif
                                        </span>

                                    @endif

                                </td>

                                {{-- Status Profil --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">

                                    @if ($mahasiswaUser->mahasiswa)

                                        @if ($mahasiswaUser->mahasiswa->status === 'active')

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

                                    @else

                                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-800 dark:border-amber-700/50 dark:bg-amber-900/30 dark:text-amber-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                            Belum dilengkapi
                                        </span>

                                    @endif

                                </td>

                                {{-- Aksi --}}
                                <td class="whitespace-nowrap px-4 py-4">

                                    <div class="flex flex-wrap items-center justify-center gap-2">

                                        @if (! $mahasiswaUser->mahasiswa)

                                            {{-- Lengkapi Profil --}}
                                            <a
                                                href="{{ route('admin.mahasiswa.profile.create', $mahasiswaUser) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white shadow-sm transition hover:bg-emerald-800"
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

                                                Lengkapi Profil
                                            </a>

                                        @else

                                            {{-- Penempatan --}}
                                            <a
                                                href="{{ route('admin.penempatans.create', ['mahasiswa_id' => $mahasiswaUser->mahasiswa->id]) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700 shadow-sm transition hover:bg-blue-100 dark:border-blue-800 dark:bg-blue-900/20 dark:text-blue-300 dark:hover:bg-blue-900/40"
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

                                                Penempatan
                                            </a>

                                            {{-- Detail --}}
                                            <a
                                                href="{{ route('admin.mahasiswa.profile.show', $mahasiswaUser) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 shadow-sm transition hover:bg-emerald-100 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/40"
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
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />

                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                    />
                                                </svg>

                                                Detail
                                            </a>

                                            {{-- Edit --}}
                                            <a
                                                href="{{ route('admin.mahasiswa.profile.edit', $mahasiswaUser) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
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
                                                        d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 20H4v-3.5L16.732 3.732z"
                                                    />
                                                </svg>

                                                Edit
                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="10"
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
                                                    d="M12 4.354a4 4 0 100 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                                />
                                            </svg>

                                        </div>

                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Belum Ada Mahasiswa
                                        </h4>

                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Akun dengan role Mahasiswa Magang akan muncul di sini.
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