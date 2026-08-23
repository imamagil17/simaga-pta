<x-simaga-layout>
    <x-slot:title>Mentor Periode - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Mentor Periode Magang</x-slot:headerTitle>

    <div class="space-y-6">

        {{-- Success Message --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 shadow-sm dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                <div class="flex items-start gap-3">
                    <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5 13l4 4L19 7"
                            />
                        </svg>
                    </div>

                    <div>
                        <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-200">
                            Berhasil
                        </p>

                        <p class="mt-0.5 text-sm text-emerald-700 dark:text-emerald-300">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Header --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            {{-- Header Top --}}
            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-start gap-4">

                        {{-- Icon --}}
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 20h5v-1a5 5 0 00-5-5m-9 6H3v-1a5 5 0 0110 0v1m-2-8a4 4 0 11-8 0 4 4 0 018 0zm11 1a3 3 0 10-6 0"
                                />
                            </svg>
                        </div>

                        <div>
                            <div class="flex flex-wrap items-center gap-2">

                                <h3 class="text-xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                                    Mentor Periode Magang
                                </h3>

                                @if ($periodeMagang->status === 'active')
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-2.5 py-1 text-[11px] font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-2.5 py-1 text-[11px] font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                        <span class="h-1.5 w-1.5 rounded-full bg-gray-400"></span>
                                        Tidak Aktif
                                    </span>
                                @endif

                            </div>

                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Kelola mentor yang ditugaskan pada periode magang ini.
                            </p>
                        </div>

                    </div>

                    {{-- Kembali --}}
                    <a
                        href="{{ route('admin.periode-magangs.index') }}"
                        class="inline-flex items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 shadow-sm transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m7 7H3"
                            />
                        </svg>

                        Kembali
                    </a>

                </div>

            </div>

            {{-- Period Summary --}}
            <div class="bg-gray-50/70 px-6 py-4 dark:bg-gray-900/20">

                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center">

                    <div class="inline-flex items-center gap-2">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                            Periode
                        </span>

                        <span class="font-semibold text-gray-900 dark:text-gray-100">
                            {{ $periodeMagang->nama_periode }}
                        </span>
                    </div>

                    <span class="hidden text-gray-300 sm:inline dark:text-gray-700">•</span>

                    <span class="rounded-lg bg-white px-2.5 py-1 font-mono text-xs font-semibold text-gray-700 shadow-sm ring-1 ring-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:ring-gray-700">
                        {{ $periodeMagang->kode_periode }}
                    </span>

                    <span class="hidden text-gray-300 sm:inline dark:text-gray-700">•</span>

                    <span class="text-xs text-gray-500 dark:text-gray-400">
                        {{ $periodeMagang->tanggal_mulai?->format('d M Y') }}
                        —
                        {{ $periodeMagang->tanggal_selesai?->format('d M Y') }}
                    </span>

                    <span class="hidden text-gray-300 sm:inline dark:text-gray-700">•</span>

                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-gray-600 dark:text-gray-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        {{ $mentorPeriodes->count() }} Mentor
                    </span>

                </div>

            </div>

        </div>

        {{-- Add Mentor --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-4 border-b border-gray-200 pb-5 dark:border-gray-700 sm:flex-row sm:items-start sm:justify-between">

                <div>
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Tambahkan Mentor
                    </h4>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pilih mentor aktif yang akan bertugas pada periode ini.
                    </p>
                </div>

                <div class="shrink-0 rounded-lg bg-emerald-50 px-3 py-2 text-xs text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-300">
                    Hanya mentor aktif
                </div>

            </div>

            @if ($periodeMagang->status === 'active')

                <form
                    method="POST"
                    action="{{ route('admin.periode-magangs.mentors.store', $periodeMagang) }}"
                    class="mt-6 space-y-5"
                >
                    @csrf

                    <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">

                        {{-- Mentor --}}
                        <div class="lg:col-span-1">
                            <label
                                for="mentor_id"
                                class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300"
                            >
                                Mentor
                            </label>

                            <select
                                id="mentor_id"
                                name="mentor_id"
                                required
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                            >
                                <option value="">Pilih Mentor</option>

                                @foreach ($availableMentors as $mentor)
                                    <option
                                        value="{{ $mentor->id }}"
                                        @selected(old('mentor_id') == $mentor->id)
                                    >
                                        {{ $mentor->user->name }}
                                        @if ($mentor->jabatan)
                                            — {{ $mentor->jabatan }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>

                            @error('mentor_id')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Status --}}
                        <div class="lg:col-span-1">
                            <label
                                for="status"
                                class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300"
                            >
                                Status Penugasan
                            </label>

                            <select
                                id="status"
                                name="status"
                                required
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                            >
                                <option
                                    value="active"
                                    @selected(old('status', 'active') === 'active')
                                >
                                    Aktif
                                </option>

                                <option
                                    value="inactive"
                                    @selected(old('status') === 'inactive')
                                >
                                    Tidak Aktif
                                </option>
                            </select>

                            @error('status')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Keterangan --}}
                        <div class="lg:col-span-1">
                            <label
                                for="keterangan"
                                class="mb-2 block text-sm font-semibold text-gray-700 dark:text-gray-300"
                            >
                                Keterangan
                            </label>

                            <input
                                type="text"
                                id="keterangan"
                                name="keterangan"
                                value="{{ old('keterangan') }}"
                                placeholder="Opsional"
                                class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm transition focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                            >

                            @error('keterangan')
                                <p class="mt-1.5 text-xs font-medium text-rose-600 dark:text-rose-400">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-5 dark:border-gray-700">
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 4v16m8-8H4"
                                />
                            </svg>

                            Tambahkan Mentor
                        </button>
                    </div>

                </form>

            @else

                <div class="mt-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/40 dark:bg-amber-900/20">

                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 9v2m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                            />
                        </svg>
                    </div>

                    <div>
                        <h5 class="text-sm font-semibold text-amber-900 dark:text-amber-200">
                            Periode Tidak Aktif
                        </h5>

                        <p class="mt-1 text-sm text-amber-800 dark:text-amber-300">
                            Mentor baru tidak dapat ditambahkan ke periode ini.
                        </p>
                    </div>

                </div>

            @endif

        </div>

        {{-- Assigned Mentors --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                    <div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                            Mentor yang Ditugaskan
                        </h4>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            Daftar mentor yang bertugas pada periode ini.
                        </p>
                    </div>

                    <span class="inline-flex w-fit items-center gap-1.5 rounded-lg border border-gray-200 bg-gray-50 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:border-gray-700 dark:bg-gray-900/40 dark:text-gray-300">
                        {{ $mentorPeriodes->count() }} Mentor
                    </span>

                </div>

            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-left text-sm text-gray-600 dark:text-gray-300">

                    <thead class="border-b border-gray-200 bg-gray-50 text-xs uppercase tracking-wider text-gray-700 dark:border-gray-700 dark:bg-gray-700/50 dark:text-gray-200">
                        <tr>
                            <th class="w-12 px-4 py-3.5 text-center">
                                No
                            </th>

                            <th class="px-4 py-3.5">
                                Mentor
                            </th>

                            <th class="px-4 py-3.5">
                                Jabatan
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

                        @forelse ($mentorPeriodes as $index => $mentorPeriode)

                            <tr class="transition-colors hover:bg-gray-50/80 dark:hover:bg-gray-700/30">

                                {{-- No --}}
                                <td class="px-4 py-4 text-center font-medium text-gray-500 dark:text-gray-400">
                                    {{ $index + 1 }}
                                </td>

                                {{-- Mentor --}}
                                <td class="px-4 py-4">

                                    <div class="flex items-center gap-3">

                                        @if ($mentorPeriode->mentor->foto)

                                            <img
                                                src="{{ \Illuminate\Support\Facades\Storage::url($mentorPeriode->mentor->foto) }}"
                                                alt="Foto {{ $mentorPeriode->mentor->user->name }}"
                                                class="h-10 w-10 rounded-full object-cover shadow-sm ring-2 ring-emerald-700/20"
                                            >

                                        @else

                                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-800 text-xs font-bold text-amber-300 shadow-sm">
                                                {{ strtoupper(substr($mentorPeriode->mentor->user->name, 0, 2)) }}
                                            </div>

                                        @endif

                                        <div class="min-w-0">

                                            <div class="truncate font-semibold text-gray-900 dark:text-gray-100">
                                                {{ $mentorPeriode->mentor->user->name }}
                                            </div>

                                            <div class="truncate text-xs text-gray-500 dark:text-gray-400">
                                                {{ $mentorPeriode->mentor->user->email }}
                                            </div>

                                        </div>

                                    </div>

                                </td>

                                {{-- Jabatan --}}
                                <td class="px-4 py-4">
                                    <div class="text-sm text-gray-800 dark:text-gray-200">
                                        {{ $mentorPeriode->mentor->jabatan ?: '-' }}
                                    </div>

                                    @if ($mentorPeriode->mentor->bagian)
                                        <div class="mt-0.5 text-xs text-gray-500 dark:text-gray-400">
                                            {{ $mentorPeriode->mentor->bagian }}
                                        </div>
                                    @endif
                                </td>

                                {{-- Status --}}
                                <td class="px-4 py-4 text-center">

                                    @if ($mentorPeriode->status === 'active')

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

                                {{-- Keterangan --}}
                                <td class="max-w-xs px-4 py-4">
                                    <div class="truncate text-sm text-gray-600 dark:text-gray-300">
                                        {{ $mentorPeriode->keterangan ?: '-' }}
                                    </div>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-4 py-4">

                                    <div class="flex items-center justify-center">

                                        <form
                                            method="POST"
                                            action="{{ route('admin.mentor-periodes.update', $mentorPeriode) }}"
                                        >
                                            @csrf
                                            @method('PUT')

                                            <input
                                                type="hidden"
                                                name="status"
                                                value="{{ $mentorPeriode->status === 'active' ? 'inactive' : 'active' }}"
                                            >

                                            @if ($mentorPeriode->status === 'active')

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 transition hover:bg-rose-100 focus:outline-none focus:ring-2 focus:ring-rose-500 dark:border-rose-800 dark:bg-rose-900/20 dark:text-rose-300 dark:hover:bg-rose-900/40"
                                                >
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M18.364 5.636l-12.728 12.728M6 5.636l12.728 12.728"
                                                        />
                                                    </svg>

                                                    Nonaktifkan
                                                </button>

                                            @else

                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center gap-1.5 rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-100 focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:border-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300 dark:hover:bg-emerald-900/40"
                                                >
                                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 13l4 4L19 7"
                                                        />
                                                    </svg>

                                                    Aktifkan
                                                </button>

                                            @endif

                                        </form>

                                    </div>

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
                                                    d="M17 20h5v-1a5 5 0 00-5-5m-9 6H3v-1a5 5 0 0110 0v1m-2-8a4 4 0 11-8 0 4 4 0 018 0zm11 1a3 3 0 10-6 0"
                                                />
                                            </svg>
                                        </div>

                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                            Belum Ada Mentor
                                        </h4>

                                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                            Belum ada mentor yang ditugaskan pada periode ini.
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