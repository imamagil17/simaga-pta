<x-simaga-layout>
    <x-slot:title>Periksa Absensi - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Periksa Absensi</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Back --}}
        <div>
            <a
                href="{{ route('mentor.absensi.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 transition hover:text-emerald-700 dark:text-gray-400 dark:hover:text-emerald-400"
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
                        d="M15 19l-7-7 7-7"
                    />
                </svg>

                Kembali ke Absensi
            </a>
        </div>

        {{-- Flash --}}
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-medium text-emerald-700 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-300">
                {{ session('success') }}
            </div>
        @endif

        {{-- Error --}}
        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">
                @foreach ($errors->all() as $error)
                    <p class="text-sm text-rose-700 dark:text-rose-300">
                        {{ $error }}
                    </p>
                @endforeach
            </div>
        @endif

        {{-- Mahasiswa --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

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

                    <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                        Menunggu Verifikasi
                    </span>

                @elseif ($absensi->status_verifikasi === 'approved')

                    <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Disetujui
                    </span>

                @else

                    <span class="inline-flex w-fit items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800 dark:bg-rose-900/40 dark:text-rose-300">
                        <span class="h-1.5 w-1.5 rounded-full bg-rose-500"></span>
                        Ditolak
                    </span>

                @endif

            </div>

        </div>

        {{-- Detail Absensi --}}
        <div class="rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">

                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Detail Absensi
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    {{ $absensi->tanggal->translatedFormat('l, d F Y') }}
                </p>

            </div>

            <div class="grid grid-cols-1 gap-4 p-6 sm:grid-cols-2 lg:grid-cols-4">

                <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Jam Masuk
                    </p>

                    <p class="mt-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                        {{ \Illuminate\Support\Str::substr($absensi->jam_masuk ?? '--:--', 0, 5) }}
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Jam Pulang
                    </p>

                    <p class="mt-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                        {{ \Illuminate\Support\Str::substr($absensi->jam_pulang ?? '--:--', 0, 5) }}
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Kehadiran
                    </p>

                    <p class="mt-2 text-xl font-bold text-emerald-700 dark:text-emerald-400">
                        {{ ucfirst($absensi->status_kehadiran) }}
                    </p>
                </div>

                <div class="rounded-xl bg-gray-50 p-4 dark:bg-gray-900/40">
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">
                        Keterlambatan
                    </p>

                    <p class="mt-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $absensi->menit_terlambat !== null ? $absensi->menit_terlambat . ' menit' : '-' }}
                    </p>
                </div>

            </div>

            @if ($absensi->keterangan)

                <div class="mx-6 mb-6 rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                        Keterangan
                    </p>

                    <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ $absensi->keterangan }}
                    </p>

                </div>

            @endif

        </div>

        {{-- Paraf Mahasiswa --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                Paraf Mahasiswa
            </h3>

            @if ($absensi->paraf_mahasiswa)

                <div class="mt-4 rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700">

                    <div class="flex min-h-40 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white dark:border-gray-600">

                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($absensi->paraf_mahasiswa) }}"
                            alt="Paraf Mahasiswa"
                            class="max-h-36 w-auto object-contain"
                        >

                    </div>

                    @if ($absensi->paraf_mahasiswa_at)
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Diparaf:
                            {{ $absensi->paraf_mahasiswa_at->translatedFormat('d F Y, H:i') }}
                        </p>
                    @endif

                </div>

            @else

                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800 dark:border-amber-900/40 dark:bg-amber-900/20 dark:text-amber-300">
                    Mahasiswa belum memberikan paraf.
                </div>

            @endif

        </div>

        {{-- Paraf Mentor --}}
        @if ($absensi->status_verifikasi === 'approved' && $absensi->paraf_mentor)

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6 dark:border-emerald-900/40 dark:bg-emerald-900/20">

                <h3 class="text-base font-bold text-emerald-900 dark:text-emerald-200">
                    Paraf Mentor
                </h3>

                <div class="mt-4 rounded-xl border border-emerald-200 bg-white p-4 dark:border-emerald-800">

                    <div class="flex min-h-40 items-center justify-center rounded-lg border border-dashed border-gray-300 bg-white dark:border-gray-600">

                        <img
                            src="{{ \Illuminate\Support\Facades\Storage::url($absensi->paraf_mentor) }}"
                            alt="Paraf Mentor"
                            class="max-h-36 w-auto object-contain"
                        >

                    </div>

                </div>

                @if ($absensi->paraf_mentor_at)
                    <p class="mt-2 text-xs text-emerald-700 dark:text-emerald-300">
                        Disetujui:
                        {{ $absensi->paraf_mentor_at->translatedFormat('d F Y, H:i') }}
                    </p>
                @endif

            </div>

        @endif

        {{-- Aksi --}}
        @if ($absensi->status_verifikasi === 'pending' && $absensi->jam_pulang !== null)

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <h3 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Tindakan Verifikasi
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Pastikan data absensi dan paraf mahasiswa sudah sesuai sebelum melakukan verifikasi.
                </p>

                {{-- Tolak --}}
                <div
                    x-data="{ open: false }"
                    class="mt-5"
                >

                    <button
                        type="button"
                        @click="open = !open"
                        class="w-full rounded-xl border border-rose-300 bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 dark:border-rose-900/60 dark:bg-rose-900/20 dark:text-rose-300"
                    >
                        Tolak Absensi
                    </button>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition
                        class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-5 dark:border-rose-900/40 dark:bg-rose-900/20"
                    >

                        <form
                            method="POST"
                            action="{{ route('mentor.absensi.reject', $absensi) }}"
                            class="space-y-4"
                        >
                            @csrf

                            <div>

                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Alasan Penolakan
                                </label>

                                <textarea
                                    name="alasan_penolakan"
                                    rows="4"
                                    required
                                    class="mt-2 w-full rounded-xl border-gray-300 bg-white text-sm shadow-sm focus:border-rose-500 focus:ring-rose-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Tuliskan alasan penolakan..."
                                ></textarea>

                            </div>

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-rose-700"
                            >
                                Tolak Absensi
                            </button>

                        </form>

                    </div>

                </div>

                {{-- ACC + Paraf --}}
                <div
                    x-data="{ open: false }"
                    class="mt-4"
                >

                    <button
                        type="button"
                        @click="open = !open"
                        class="w-full rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800"
                    >
                        ACC & Paraf Mentor
                    </button>

                    <div
                        x-show="open"
                        x-cloak
                        x-transition
                        class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-5 dark:border-emerald-900/40 dark:bg-emerald-900/20"
                    >

                        <form
                            method="POST"
                            action="{{ route('mentor.absensi.approve', $absensi) }}"
                            class="space-y-5"
                        >
                            @csrf

                            <x-signature-pad
                                name="paraf_mentor"
                                label="Paraf Mentor"
                                :required="true"
                            />

                            <button
                                type="submit"
                                class="w-full rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-emerald-800"
                            >
                                Simpan Paraf & ACC Absensi
                            </button>

                        </form>

                    </div>

                </div>

            </div>

        @elseif ($absensi->status_verifikasi === 'rejected')

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6 dark:border-rose-900/40 dark:bg-rose-900/20">

                <h3 class="text-base font-bold text-rose-900 dark:text-rose-200">
                    Alasan Penolakan
                </h3>

                <p class="mt-2 text-sm text-rose-800 dark:text-rose-300">
                    {{ $absensi->alasan_penolakan ?? 'Tidak ada alasan yang dicatat.' }}
                </p>

            </div>

        @endif

    </div>
</x-simaga-layout>