<x-simaga-layout>
    <x-slot:title>Detail Mentor - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Mentor</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Header --}}
        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <a
                    href="{{ route('admin.mentors.index') }}"
                    class="mb-2 inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 hover:underline dark:text-emerald-400"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                        />
                    </svg>

                    Kembali ke Data Mentor
                </a>

                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                    Detail Profil Mentor
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Informasi lengkap profil mentor yang terdaftar pada SIMAGA PTA.
                </p>
            </div>

            <div class="flex items-center gap-2">

                @if ($mentor->status === 'active')
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

        {{-- Identity Card --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-950 px-6 py-8 text-white">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center">

                    {{-- Foto --}}
                    <div class="shrink-0">

                        @if ($mentor->foto)

                            <img
                                src="{{ \Illuminate\Support\Facades\Storage::url($mentor->foto) }}"
                                alt="Foto {{ $user->name }}"
                                class="h-28 w-28 rounded-2xl object-cover shadow-lg ring-4 ring-white/20"
                            >

                        @else

                            <div class="flex h-28 w-28 items-center justify-center rounded-2xl bg-emerald-700 text-3xl font-bold text-amber-300 shadow-lg ring-4 ring-white/10">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>

                        @endif

                    </div>

                    {{-- Identitas --}}
                    <div class="min-w-0">
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-400/30 bg-amber-400/10 px-3 py-1 text-xs font-semibold text-amber-200">
                            Mentor SIMAGA PTA
                        </span>

                        <h2 class="mt-3 text-2xl font-bold tracking-tight">
                            {{ $user->name }}
                        </h2>

                        <p class="mt-1 text-sm text-emerald-200">
                            {{ $user->email }}
                        </p>

                        @if ($mentor->jabatan)
                            <p class="mt-2 text-sm font-medium text-white/90">
                                {{ $mentor->jabatan }}
                            </p>
                        @endif

                        @if ($mentor->bagian)
                            <p class="text-xs text-emerald-200">
                                {{ $mentor->bagian }}
                            </p>
                        @endif
                    </div>

                </div>
            </div>

            {{-- Informasi Akun --}}
            <div class="border-b border-gray-200 p-6 dark:border-gray-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Informasi Akun
                </h4>

                <div class="mt-4 grid grid-cols-1 gap-5 md:grid-cols-3">

                    <div>
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Nama Lengkap
                        </div>

                        <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $user->name }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Alamat Email
                        </div>

                        <div class="mt-1 break-all text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $user->email }}
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Role Sistem
                        </div>

                        <div class="mt-1">
                            <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                Mentor
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Data Kepegawaian --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Data Kepegawaian
                </h4>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Informasi kepegawaian dan unit kerja mentor.
                </p>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        NIP
                    </div>

                    <div class="mt-1 font-mono text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mentor->nip ?: '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Jabatan
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mentor->jabatan ?: '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Bagian / Unit Kerja
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mentor->bagian ?: '-' }}
                    </div>
                </div>

            </div>

        </div>

        {{-- Informasi Pribadi --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Informasi Pribadi
                </h4>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Data pribadi dan kontak mentor.
                </p>
            </div>

            <div class="mt-6 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">

                <div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Jenis Kelamin
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mentor->jenis_kelamin ?: '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Agama
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mentor->agama ?: '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        No. HP
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mentor->no_hp ?: '-' }}
                    </div>
                </div>

            </div>

        </div>

        {{-- Keterangan --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Keterangan
                </h4>
            </div>

            <div class="mt-5 rounded-xl bg-gray-50 p-4 text-sm leading-7 text-gray-700 dark:bg-gray-900/40 dark:text-gray-300">
                {{ $mentor->keterangan ?: 'Tidak ada keterangan tambahan.' }}
            </div>

        </div>

        {{-- Metadata --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                <div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Profil dibuat
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mentor->created_at?->format('d F Y, H:i') ?? '-' }}
                    </div>
                </div>

                <div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Terakhir diperbarui
                    </div>

                    <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-gray-100">
                        {{ $mentor->updated_at?->format('d F Y, H:i') ?? '-' }}
                    </div>
                </div>

            </div>

        </div>

        {{-- Actions --}}
        <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

            <a
                href="{{ route('admin.mentors.index') }}"
                class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
            >
                Kembali
            </a>

            <a
                href="{{ route('admin.mentors.profile.edit', $user) }}"
                class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800"
            >
                Edit Profil
            </a>

        </div>

    </div>
</x-simaga-layout>