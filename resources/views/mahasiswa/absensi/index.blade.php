<x-simaga-layout>
    <x-slot:title>Absensi Magang - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Absensi Magang</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Success --}}
        @if (session('success'))
        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
            <div class="flex items-center gap-2">
                <svg
                    class="h-5 w-5 shrink-0"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 13l4 4L19 7" />
                </svg>

                {{ session('success') }}
            </div>
        </div>
        @endif

        {{-- Error --}}
        @if ($errors->any())
        <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">
            <div class="space-y-1">

                @foreach ($errors->all() as $error)
                <p class="text-sm text-rose-700 dark:text-rose-300">
                    {{ $error }}
                </p>
                @endforeach

            </div>
        </div>
        @endif

        {{-- Tidak ada penempatan --}}
        @if (! $penempatan)

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-8 text-center dark:border-amber-900/40 dark:bg-amber-900/20">

            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                <svg
                    class="h-7 w-7"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.8"
                        d="M8 7h8m-8 4h8m-8 4h5M5 3h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                </svg>
            </div>

            <h3 class="mt-4 text-base font-bold text-amber-900 dark:text-amber-200">
                Belum Ada Penempatan Aktif
            </h3>

            <p class="mt-1 text-sm text-amber-700 dark:text-amber-300">
                Anda belum memiliki penempatan magang aktif sehingga belum dapat melakukan absensi.
            </p>

        </div>

        @else

        {{-- Informasi Penempatan --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Periode Magang
                    </p>

                    <h3 class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->periodeMagang->nama_periode }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Mentor:
                        <span class="font-semibold">
                            {{ $penempatan->mentor->user->name }}
                        </span>
                    </p>

                </div>

                <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                    <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                    Penempatan Aktif
                </span>

            </div>

        </div>

        {{-- Absensi Hari Ini --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                            Absensi Hari Ini
                        </h3>

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ now()->translatedFormat('l, d F Y') }}
                        </p>

                    </div>

                    {{-- Riwayat Absensi --}}
                    <a
                        href="{{ route('mahasiswa.absensi.riwayat') }}"
                        class="inline-flex w-fit items-center justify-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
                        <svg
                            class="h-4 w-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7h8M8 11h8M8 15h5M5 3h10a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z" />
                        </svg>

                        Riwayat Absensi
                    </a>

                </div>

            </div>

            <div class="p-6">

                {{-- =========================================================
                         BELUM ABSEN MASUK
                         ========================================================= --}}
                @if (! $absensiHariIni)

                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-6 dark:border-blue-900/40 dark:bg-blue-900/20">

                    <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                        <div>

                            <p class="text-xs font-medium uppercase tracking-wide text-blue-600 dark:text-blue-400">
                                Status Hari Ini
                            </p>

                            <h4 class="mt-1 text-lg font-bold text-blue-900 dark:text-blue-200">
                                Belum Absen Masuk
                            </h4>

                            <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                                Silakan lakukan absen masuk saat mulai magang.
                            </p>

                        </div>

                        <form
                            method="POST"
                            action="{{ route('mahasiswa.absensi.masuk') }}">
                            @csrf

                            <button
                                type="submit"
                                class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 sm:w-auto">
                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                                Absen Masuk
                            </button>

                        </form>

                    </div>

                </div>

                @else

                {{-- =====================================================
                             SUDAH ABSEN MASUK
                             ===================================================== --}}
                <div class="space-y-6">

                    {{-- Jam masuk & pulang --}}
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                        {{-- Jam Masuk --}}
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">

                            <div class="flex items-center gap-2">

                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6v6l4 2" />
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                            stroke-width="2" />
                                    </svg>
                                </div>

                                <p class="text-xs font-medium uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                                    Jam Masuk
                                </p>

                            </div>

                            <p class="mt-3 text-2xl font-bold text-emerald-900 dark:text-emerald-200">
                                {{ $absensiHariIni->jam_masuk
                                            ? \Illuminate\Support\Str::substr($absensiHariIni->jam_masuk, 0, 5)
                                            : '--:--'
                                        }}
                            </p>

                        </div>

                        {{-- Jam Pulang --}}
                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-900/40">

                            <div class="flex items-center gap-2">

                                <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 6v6l4 2" />
                                        <circle
                                            cx="12"
                                            cy="12"
                                            r="9"
                                            stroke-width="2" />
                                    </svg>
                                </div>

                                <p class="text-xs font-medium uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                    Jam Pulang
                                </p>

                            </div>

                            <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-gray-100">
                                {{ $absensiHariIni->jam_pulang
                                            ? \Illuminate\Support\Str::substr($absensiHariIni->jam_pulang, 0, 5)
                                            : '--:--'
                                        }}
                            </p>

                        </div>

                    </div>

                    {{-- Status --}}
                    <div class="flex flex-wrap items-center gap-3">

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">

                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>

                            Hadir

                        </span>

                        @if ($absensiHariIni->menit_terlambat !== null)

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">

                            <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>

                            Terlambat {{ $absensiHariIni->menit_terlambat }} menit

                        </span>

                        @endif

                        @if ($absensiHariIni->status_verifikasi === 'pending')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                            Menunggu Verifikasi Mentor
                        </span>

                        @elseif ($absensiHariIni->status_verifikasi === 'approved')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                            Disetujui Mentor
                        </span>

                        @elseif ($absensiHariIni->status_verifikasi === 'rejected')

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                            Ditolak Mentor
                        </span>

                        @endif

                    </div>

                    {{-- Keterangan keterlambatan --}}
                    @if ($absensiHariIni->menit_terlambat !== null)

                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/40 dark:bg-amber-900/20 dark:text-amber-300">

                        Anda datang
                        <strong>
                            {{ $absensiHariIni->menit_terlambat }} menit
                        </strong>
                        setelah jam masuk normal.

                    </div>

                    @endif

                    {{-- =================================================
                                 ABSEN PULANG
                                 ================================================= --}}
                    @if ($absensiHariIni->jam_pulang === null)

                    @php
                    $workSchedule = app(\App\Services\WorkScheduleService::class);
                    $hariIni = now();
                    $jamPulangNormal = $workSchedule->jamPulang($hariIni);

                    $sudahWaktunyaPulang = $jamPulangNormal !== null
                    && $hariIni->format('H:i') >= $jamPulangNormal;
                    @endphp

                    <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20">

                        <div class="flex items-start gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 dark:bg-emerald-900/50 dark:text-emerald-300">

                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>

                            </div>

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                                    Absen Pulang
                                </p>

                                @if ($jamPulangNormal)

                                <h4 class="mt-1 text-base font-bold text-emerald-900 dark:text-emerald-200">
                                    Jam pulang hari ini:
                                    {{ $jamPulangNormal }}
                                </h4>

                                @endif

                            </div>

                        </div>

                        @if (! $jamPulangNormal)

                        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/40 dark:bg-amber-900/20 dark:text-amber-300">

                            Absensi pulang tidak tersedia pada hari Sabtu dan Minggu.

                        </div>

                        @elseif (! $sudahWaktunyaPulang)

                        <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/40 dark:bg-amber-900/20 dark:text-amber-300">

                            Absen pulang belum tersedia.

                            <div class="mt-1 font-semibold">
                                Absen pulang mulai pukul {{ $jamPulangNormal }}.
                            </div>

                        </div>

                        @else

                        <div class="mt-5">

                            <p class="mb-4 text-sm text-emerald-700 dark:text-emerald-300">
                                Jam pulang sudah tersedia. Silakan bubuhkan paraf untuk menyelesaikan absensi hari ini.
                            </p>

                            <form
                                method="POST"
                                action="{{ route('mahasiswa.absensi.pulang') }}"
                                class="space-y-5">
                                @csrf

                                <x-signature-pad
                                    name="paraf_mahasiswa"
                                    label="Paraf Mahasiswa"
                                    :required="true" />

                                <button
                                    type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2">
                                    <svg
                                        class="h-5 w-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>

                                    Simpan Paraf & Absen Pulang
                                </button>

                            </form>

                        </div>

                        @endif

                    </div>

                    {{-- =================================================
                                 ABSEN PULANG SUDAH DILAKUKAN
                                 ================================================= --}}
                    @else

                    <div class="rounded-2xl border border-blue-200 bg-blue-50 p-5 dark:border-blue-900/40 dark:bg-blue-900/20">

                        <div class="flex items-start gap-3">

                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300">

                                <svg
                                    class="h-5 w-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>

                            </div>

                            <div>

                                <p class="text-xs font-semibold uppercase tracking-wide text-blue-600 dark:text-blue-400">
                                    Absensi Selesai
                                </p>

                                <h4 class="mt-1 text-base font-bold text-blue-900 dark:text-blue-200">
                                    Absen pulang:
                                    {{ \Illuminate\Support\Str::substr($absensiHariIni->jam_pulang, 0, 5) }}
                                </h4>

                                <p class="mt-1 text-sm text-blue-700 dark:text-blue-300">
                                    Data absensi telah dikirim untuk diperiksa oleh mentor.
                                </p>

                            </div>

                        </div>

                        @if ($absensiHariIni->paraf_mahasiswa)

                        <div class="mt-5 rounded-xl border border-blue-200 bg-white p-4 dark:border-blue-800 dark:bg-gray-900">

                            <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                                Paraf Mahasiswa
                            </p>

                            <div class="flex min-h-28 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white p-3 dark:border-gray-700">

                                <img
                                    src="{{ \Illuminate\Support\Facades\Storage::url($absensiHariIni->paraf_mahasiswa) }}"
                                    alt="Paraf Mahasiswa"
                                    class="max-h-28 w-auto object-contain">

                            </div>

                            @if ($absensiHariIni->paraf_mahasiswa_at)

                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                Diparaf pada
                                {{ $absensiHariIni->paraf_mahasiswa_at->translatedFormat('d F Y, H:i') }}
                            </p>

                            @endif

                        </div>

                        @endif

                    </div>

                    @endif

                </div>

                @endif

            </div>

        </div>

        @endif

    </div>
</x-simaga-layout>