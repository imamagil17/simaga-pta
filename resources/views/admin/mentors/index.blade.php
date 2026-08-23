<x-simaga-layout>
    <x-slot:title>Data Mentor - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Data Mentor</x-slot:headerTitle>

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
                    <svg class="h-6 w-6 text-emerald-700 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 4.354a4 4 0 100 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                        />
                    </svg>

                    Data Mentor
                </h3>

                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                    Daftar mentor yang terdaftar dalam SIMAGA PTA.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:border-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                    Total: {{ $mentors->count() }} Mentor
                </span>
            </div>
        </div>

        {{-- Mentor List Table Card --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">

                    <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wider text-gray-700 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-200">
                        <tr>
                            <th scope="col" class="w-12 px-4 py-3.5 text-center">
                                No
                            </th>

                            <th scope="col" class="px-4 py-3.5">
                                Mentor
                            </th>

                            <th scope="col" class="px-4 py-3.5">
                                NIP
                            </th>

                            <th scope="col" class="px-4 py-3.5">
                                Jabatan / Bagian
                            </th>

                            <th scope="col" class="px-4 py-3.5">
                                Jenis Kelamin
                            </th>

                            <th scope="col" class="px-4 py-3.5">
                                Agama
                            </th>

                            <th scope="col" class="px-4 py-3.5">
                                No. HP
                            </th>

                            {{-- Status Akun --}}
                            <th scope="col" class="px-4 py-3.5 text-center">
                                Status Akun
                            </th>

                            {{-- Status Profil --}}
                            <th scope="col" class="px-4 py-3.5 text-center">
                                Status Profil
                            </th>

                            <th scope="col" class="px-4 py-3.5 text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700/60">

                        @forelse($mentors as $index => $mentorUser)

                            <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-gray-700/30">

                                <td class="px-4 py-4 text-center font-medium text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Mentor --}}
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">

                                        @if ($mentorUser->mentor?->foto)
                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::url($mentorUser->mentor->foto) }}"
                                                alt="Foto {{ $mentorUser->name }}"
                                                class="h-10 w-10 shrink-0 rounded-full object-cover shadow-sm ring-2 ring-emerald-700/30"
                                            >
                                        @else
                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-800 text-xs font-bold text-amber-300 shadow-sm ring-1 ring-emerald-600/30">
                                                {{ strtoupper(substr($mentorUser->name, 0, 2)) }}
                                            </div>
                                        @endif

                                        <div class="min-w-0">
                                            <div class="truncate font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $mentorUser->name }}
                                            </div>

                                            <div class="truncate text-xs text-gray-500 dark:text-gray-400">
                                                {{ $mentorUser->email }}
                                            </div>
                                        </div>

                                    </div>
                                </td>

                                {{-- NIP --}}
                                <td class="whitespace-nowrap px-4 py-4">
                                    @if($mentorUser->mentor?->nip)
                                        <span class="font-mono text-xs text-gray-800 dark:text-gray-200">
                                            {{ $mentorUser->mentor->nip }}
                                        </span>
                                    @else
                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>
                                    @endif
                                </td>

                                {{-- Jabatan / Bagian --}}
                                <td class="px-4 py-4">
                                    @if($mentorUser->mentor?->jabatan || $mentorUser->mentor?->bagian)

                                        <div class="text-xs font-medium text-gray-800 dark:text-gray-200">
                                            {{ $mentorUser->mentor->jabatan ?? 'Belum dilengkapi' }}
                                        </div>

                                        <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                            {{ $mentorUser->mentor->bagian ?? 'Belum dilengkapi' }}
                                        </div>

                                    @else

                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>

                                    @endif
                                </td>

                                {{-- Jenis Kelamin --}}
                                <td class="whitespace-nowrap px-4 py-4">
                                    @if($mentorUser->mentor?->jenis_kelamin)
                                        <span class="text-xs text-gray-800 dark:text-gray-200">
                                            {{ $mentorUser->mentor->jenis_kelamin }}
                                        </span>
                                    @else
                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>
                                    @endif
                                </td>

                                {{-- Agama --}}
                                <td class="whitespace-nowrap px-4 py-4">
                                    @if($mentorUser->mentor?->agama)
                                        <span class="text-xs text-gray-800 dark:text-gray-200">
                                            {{ $mentorUser->mentor->agama }}
                                        </span>
                                    @else
                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>
                                    @endif
                                </td>

                                {{-- No HP --}}
                                <td class="whitespace-nowrap px-4 py-4">
                                    @if($mentorUser->mentor?->no_hp)
                                        <span class="font-mono text-xs text-gray-800 dark:text-gray-200">
                                            {{ $mentorUser->mentor->no_hp }}
                                        </span>
                                    @else
                                        <span class="text-xs italic text-gray-400 dark:text-gray-500">
                                            Belum dilengkapi
                                        </span>
                                    @endif
                                </td>

                                {{-- Status Akun --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">
                                    @if ($mentorUser->status === 'active')
                                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-blue-500"></span>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            Tidak Aktif
                                        </span>
                                    @endif
                                </td>

                                {{-- Status Profil --}}
                                <td class="whitespace-nowrap px-4 py-4 text-center">
                                    @if($mentorUser->mentor)

                                        @if($mentorUser->mentor->status === 'active')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-100 px-2.5 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300">
                                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                                Nonaktif
                                            </span>
                                        @endif

                                    @else

                                        <span class="inline-flex items-center gap-1 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-xs font-medium text-amber-800 dark:border-amber-700/50 dark:bg-amber-900/30 dark:text-amber-300">
                                            <span class="h-1.5 w-1.5 rounded-full bg-amber-400"></span>
                                            Belum dilengkapi
                                        </span>

                                    @endif
                                </td>

                                {{-- Aksi --}}
                                <td class="whitespace-nowrap px-4 py-4">
                                    <div class="flex items-center justify-center gap-2">

                                        @if(!$mentorUser->mentor)

                                            <a
                                                href="{{ route('admin.mentors.profile.create', $mentorUser) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

                                            {{-- Lihat Detail --}}
                                            <a
                                                href="{{ route('admin.mentors.profile.show', $mentorUser) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/40"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7-1.274-4.057-5.065-7-9.542-7z"
                                                    />
                                                </svg>

                                                Detail
                                            </a>

                                            {{-- Edit Profil --}}
                                            <a
                                                href="{{ route('admin.mentors.profile.edit', $mentorUser) }}"
                                                class="inline-flex items-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M11 5h2m-1-1v2m7.071 3.071l1.414 1.414M19 13v2m-2 4H7a2 2 0 01-2-2V7a2 2 0 012-2h5"
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
                                <td colspan="10" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center gap-2">

                                        <svg class="h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 4.354a4 4 0 100 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"
                                            />
                                        </svg>

                                        <p class="text-sm font-semibold">
                                            Belum ada mentor terdaftar
                                        </p>

                                        <p class="text-xs text-gray-400">
                                            Mentor yang dibuat melalui Manajemen Pengguna akan muncul di sini.
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