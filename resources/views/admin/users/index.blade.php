<x-simaga-layout>
    <x-slot:title>Manajemen Pengguna - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Manajemen Pengguna</x-slot:headerTitle>

    <div class="space-y-6">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Header Halaman --}}
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h3 class="text-xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                    Manajemen Pengguna
                </h3>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Kelola akun pengguna yang terdaftar dalam sistem SIMAGA PTA.
                </p>
            </div>

            <div class="inline-flex items-center gap-2 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-sm font-medium text-emerald-700 dark:border-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                    />
                </svg>

                {{ $users->count() }} Pengguna
            </div>
        </div>

        {{-- Tabel Pengguna --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Daftar Pengguna
                </h4>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Seluruh akun pengguna SIMAGA PTA.
                </p>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

                    <thead class="bg-gray-50 dark:bg-gray-900/40">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                No
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Nama
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Email
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Role
                            </th>

                            <th class="px-6 py-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Status
                            </th>

                            <th class="px-6 py-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">

                        @forelse ($users as $user)

                            <tr class="transition hover:bg-gray-50 dark:hover:bg-gray-700/30">

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                    {{ $loop->iteration }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}
                                    </div>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                    {{ $user->email }}
                                </td>

                                <td class="whitespace-nowrap px-6 py-4">

                                    @php
                                        $roleLabel = match ($user->role) {
                                            'administrator' => 'Administrator PTA',
                                            'mentor' => 'Mentor',
                                            'mahasiswa' => 'Mahasiswa Magang',
                                            default => ucfirst($user->role),
                                        };
                                    @endphp

                                    @if ($user->role === 'administrator')
                                        <span class="inline-flex rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                                            {{ $roleLabel }}
                                        </span>
                                    @elseif ($user->role === 'mentor')
                                        <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                                            {{ $roleLabel }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 dark:bg-blue-900/30 dark:text-blue-300">
                                            {{ $roleLabel }}
                                        </span>
                                    @endif

                                </td>

                                <td class="whitespace-nowrap px-6 py-4">

                                    @if ($user->status === 'active')
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Tidak Aktif
                                        </span>
                                    @endif

                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <span class="text-xs font-medium text-gray-400">
                                        Segera tersedia
                                    </span>
                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">

                                    <div class="mx-auto flex max-w-sm flex-col items-center">

                                        <div class="mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400">
                                            <svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="1.8"
                                                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                            </svg>
                                        </div>

                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Belum Ada Pengguna
                                        </h4>

                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Belum terdapat akun pengguna yang terdaftar pada sistem.
                                        </p>

                                    </div>

                                </td>
                            </tr>

                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>

        {{-- Informasi --}}
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">
            <div class="flex gap-3">

                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>

                <div>
                    <h4 class="text-sm font-semibold text-emerald-900 dark:text-emerald-200">
                        Informasi Pengguna
                    </h4>

                    <p class="mt-1 text-sm leading-6 text-emerald-700 dark:text-emerald-300">
                        Password pengguna tidak ditampilkan pada halaman ini.
                        Pengelolaan akun seperti penambahan pengguna, perubahan status,
                        dan reset password akan tersedia pada tahap pengembangan berikutnya.
                    </p>
                </div>

            </div>
        </div>

    </div>
</x-simaga-layout>