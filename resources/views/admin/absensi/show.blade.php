<x-simaga-layout>

    <x-slot:title>Detail Absensi - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Absensi</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        <div>
            <a
                href="{{ route('admin.absensi.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 dark:text-gray-400 dark:hover:text-emerald-400">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15 19l-7-7 7-7" />
                </svg>

                Kembali ke Monitoring
            </a>
        </div>

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Mahasiswa
                    </p>

                    <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $absensi->penempatan->mahasiswa->user->name }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        NIM: {{ $absensi->penempatan->mahasiswa->nim }}
                    </p>

                </div>

                @if ($absensi->status_verifikasi === 'pending')

                <span class="inline-flex w-fit rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                    Menunggu
                </span>

                @elseif ($absensi->status_verifikasi === 'approved')

                <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                    Disetujui
                </span>

                @else

                <span class="inline-flex w-fit rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                    Ditolak
                </span>

                @endif

            </div>

        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Tanggal
                </p>

                <p class="mt-2 font-bold text-gray-900 dark:text-gray-100">
                    {{ $absensi->tanggal->translatedFormat('d F Y') }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Jam Masuk
                </p>

                <p class="mt-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                    {{ \Illuminate\Support\Str::substr($absensi->jam_masuk ?? '--:--', 0, 5) }}
                </p>
            </div>

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">
                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Jam Pulang
                </p>

                <p class="mt-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                    {{ \Illuminate\Support\Str::substr($absensi->jam_pulang ?? '--:--', 0, 5) }}
                </p>
            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/40 dark:bg-amber-900/20">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 dark:text-amber-400">
                    Keterlambatan
                </p>

                <p class="mt-2 text-xl font-bold text-amber-800 dark:text-amber-300">
                    {{ $absensi->menit_terlambat !== null ? $absensi->menit_terlambat . ' menit' : '-' }}
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Paraf Mahasiswa --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Paraf Mahasiswa
                </h3>

                @if ($absensi->paraf_mahasiswa)

                <div class="mt-4 flex min-h-40 items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white p-4 dark:border-gray-600">
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($absensi->paraf_mahasiswa) }}"
                        alt="Paraf Mahasiswa"
                        class="max-h-32 w-auto object-contain">
                </div>

                @if ($absensi->paraf_mahasiswa_at)
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    {{ $absensi->paraf_mahasiswa_at->translatedFormat('d F Y, H:i') }}
                </p>
                @endif

                @else

                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Belum ada paraf mahasiswa.
                </p>

                @endif

            </div>

            {{-- Paraf Mentor --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <h3 class="font-bold text-gray-900 dark:text-gray-100">
                    Paraf Mentor
                </h3>

                @if ($absensi->paraf_mentor)

                <div class="mt-4 flex min-h-40 items-center justify-center rounded-xl border border-dashed border-gray-300 bg-white p-4 dark:border-gray-600">
                    <img
                        src="{{ \Illuminate\Support\Facades\Storage::url($absensi->paraf_mentor) }}"
                        alt="Paraf Mentor"
                        class="max-h-32 w-auto object-contain">
                </div>

                @if ($absensi->paraf_mentor_at)
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    {{ $absensi->paraf_mentor_at->translatedFormat('d F Y, H:i') }}
                </p>
                @endif

                @else

                <p class="mt-4 text-sm text-gray-500 dark:text-gray-400">
                    Belum ada paraf mentor.
                </p>

                @endif

            </div>

        </div>

        @if ($absensi->alasan_penolakan)

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6 dark:border-rose-900/40 dark:bg-rose-900/20">

            <h3 class="font-bold text-rose-900 dark:text-rose-200">
                Alasan Penolakan
            </h3>

            <p class="mt-2 text-sm text-rose-800 dark:text-rose-300">
                {{ $absensi->alasan_penolakan }}
            </p>

        </div>

        @endif

    </div>
</x-simaga-layout>