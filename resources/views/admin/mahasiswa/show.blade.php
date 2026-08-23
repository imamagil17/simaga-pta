<x-simaga-layout>
    <x-slot:title>Detail Mahasiswa - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Mahasiswa</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div class="flex items-center gap-4">

                    @if ($mahasiswa->foto)

                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($mahasiswa->foto) }}"
                            alt="Foto {{ $user->name }}"
                            class="h-16 w-16 rounded-full object-cover shadow-sm ring-2 ring-emerald-700/20"
                        >

                    @else

                        <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-full bg-emerald-800 text-lg font-bold text-amber-300 shadow-sm">
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        </div>

                    @endif

                    <div>
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                            {{ $user->name }}
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $user->email }}
                        </p>
                    </div>

                </div>

                <div class="flex items-center gap-2">

                    <a
                        href="{{ route('admin.mahasiswa.index') }}"
                        class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
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
                        href="{{ route('admin.mahasiswa.profile.edit', $user) }}"
                        class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L7.5 20H4v-3.5L16.732 3.732z"
                            />
                        </svg>

                        Edit
                    </a>

                </div>

            </div>

        </div>

        {{-- Status --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-900/40 dark:bg-blue-900/20">

                <p class="text-xs font-medium uppercase tracking-wide text-blue-600 dark:text-blue-400">
                    Status Akun
                </p>

                <div class="mt-2">

                    @if ($user->status === 'active')
                        <span class="inline-flex items-center gap-2 text-sm font-semibold text-blue-800 dark:text-blue-300">
                            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 text-sm font-semibold text-red-700 dark:text-red-300">
                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                            Tidak Aktif
                        </span>
                    @endif

                </div>

            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <p class="text-xs font-medium uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Status Profil
                </p>

                <div class="mt-2">

                    @if ($mahasiswa->status === 'active')
                        <span class="inline-flex items-center gap-2 text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <span class="h-2 w-2 rounded-full bg-gray-400"></span>
                            Tidak Aktif
                        </span>
                    @endif

                </div>

            </div>

        </div>

        {{-- Akademik --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Data Akademik
                </h4>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        NIM
                    </p>

                    <p class="mt-1 font-mono text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mahasiswa->nim }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Perguruan Tinggi
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mahasiswa->perguruan_tinggi }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Program Studi
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mahasiswa->program_studi }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Pribadi --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Informasi Pribadi
                </h4>
            </div>

            <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2">

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Jenis Kelamin
                    </p>

                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $mahasiswa->jenis_kelamin ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Agama
                    </p>

                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $mahasiswa->agama ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        No. HP
                    </p>

                    <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ $mahasiswa->no_hp ?: '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Alamat
                    </p>

                    <p class="mt-1 text-sm leading-6 text-gray-900 dark:text-gray-100">
                        {{ $mahasiswa->alamat ?: '-' }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Keterangan --}}
        @if ($mahasiswa->keterangan)
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Keterangan
                </h4>

                <p class="mt-2 whitespace-pre-line text-sm leading-6 text-gray-600 dark:text-gray-300">
                    {{ $mahasiswa->keterangan }}
                </p>

            </div>
        @endif

    </div>
</x-simaga-layout>