<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    @endphp

    <x-slot:title>Pemeriksaan Tugas - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Pemeriksaan Tugas</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Flash --}}
        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">

            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </p>

        </div>

        @endif

        {{-- Error --}}
        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">

            <ul class="space-y-1 text-sm text-rose-800 dark:text-rose-300">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        {{-- Back --}}
        <div>

            <a
                href="{{ route('mentor.tugas.show', $tugas) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Detail Tugas
            </a>

        </div>

        {{-- Identitas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mahasiswa
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">
                        {{ $pengumpulan->mahasiswa->user->name }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $pengumpulan->mahasiswa->nim }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Tugas
                    </p>

                    <p class="mt-1 font-bold text-gray-900 dark:text-gray-100">
                        {{ $tugas->judul }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Dikumpulkan
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $pengumpulan->dikumpulkan_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Status --}}
        <div class="flex flex-col gap-3 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Status Pengumpulan
                </p>

            </div>

            @switch($pengumpulan->status)

            @case('submitted')

            <span class="inline-flex w-fit rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                Menunggu Pemeriksaan
            </span>

            @break

            @case('reviewed')

            <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                Sudah Dinilai
            </span>

            @break

            @case('revision')

            <span class="inline-flex w-fit rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800">
                Perlu Revisi
            </span>

            @break

            @default

            <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                {{ ucfirst($pengumpulan->status) }}
            </span>

            @endswitch

        </div>

        {{-- Jawaban --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400">
                    Jawaban Mahasiswa
                </p>

                @if ($pengumpulan->jawaban)

                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-gray-700 dark:text-gray-300">
                    {{ $pengumpulan->jawaban }}
                </p>

                @else

                <p class="mt-3 text-sm italic text-gray-500">
                    Mahasiswa tidak mengisi jawaban teks.
                </p>

                @endif

            </div>

            {{-- File --}}
            @if ($pengumpulan->file_jawaban)

            <div class="mt-6 border-t border-gray-100 pt-6 dark:border-gray-700">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    File Jawaban
                </p>

                <a
                    href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-3 inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Buka File Jawaban
                </a>

            </div>

            @endif

        </div>

        {{-- Hasil sebelumnya --}}
        @if ($pengumpulan->status === 'reviewed')

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Hasil Penilaian
            </p>

            <p class="mt-2 text-4xl font-bold text-emerald-800">
                {{ $pengumpulan->nilai }}
            </p>

            @if ($pengumpulan->catatan_mentor)

            <p class="mt-4 whitespace-pre-line text-sm leading-7 text-emerald-800">
                {{ $pengumpulan->catatan_mentor }}
            </p>

            @endif

        </div>

        @endif

        {{-- Form Review --}}
        @if ($pengumpulan->status === 'submitted')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Nilai --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

                <h3 class="font-bold text-emerald-900">
                    Beri Penilaian
                </h3>

                <p class="mt-1 text-sm text-emerald-800">
                    Nilai tugas dari 0 sampai 100.
                </p>

                <form
                    method="POST"
                    action="{{ route('mentor.tugas.review', [$tugas, $pengumpulan]) }}"
                    class="mt-5 space-y-4">

                    @csrf

                    <div>

                        <label
                            for="nilai"
                            class="block text-sm font-semibold text-emerald-900">
                            Nilai
                        </label>

                        <input
                            type="number"
                            id="nilai"
                            name="nilai"
                            value="{{ old('nilai') }}"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                            class="mt-2 block w-full rounded-xl border-emerald-300 bg-white text-sm">

                    </div>

                    <div>

                        <label
                            for="catatan_mentor"
                            class="block text-sm font-semibold text-emerald-900">
                            Catatan
                        </label>

                        <textarea
                            id="catatan_mentor"
                            name="catatan_mentor"
                            rows="5"
                            placeholder="Berikan catatan atau feedback..."
                            class="mt-2 block w-full rounded-xl border-emerald-300 bg-white text-sm">{{ old('catatan_mentor') }}</textarea>

                    </div>

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                        Simpan Penilaian
                    </button>

                </form>

            </div>

            {{-- Revisi --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6">

                <h3 class="font-bold text-rose-900">
                    Minta Revisi
                </h3>

                <p class="mt-1 text-sm text-rose-800">
                    Gunakan jika jawaban belum sesuai.
                </p>

                <form
                    method="POST"
                    action="{{ route('mentor.tugas.revision', [$tugas, $pengumpulan]) }}"
                    class="mt-5 space-y-4">

                    @csrf

                    <div>

                        <label
                            for="catatan_revisi"
                            class="block text-sm font-semibold text-rose-900">
                            Catatan Revisi
                        </label>

                        <textarea
                            id="catatan_revisi"
                            name="catatan_mentor"
                            rows="6"
                            required
                            minlength="5"
                            placeholder="Jelaskan bagian yang harus diperbaiki..."
                            class="mt-2 block w-full rounded-xl border-rose-300 bg-white text-sm">{{ old('catatan_mentor') }}</textarea>

                    </div>

                    <button
                        type="submit"
                        class="rounded-xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white hover:bg-rose-700">
                        Minta Revisi
                    </button>

                </form>

            </div>

        </div>

        @endif

        {{-- Info review --}}
        @if ($pengumpulan->reviewer)

        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Pemeriksaan
            </p>

            <p class="mt-2 text-sm text-gray-700">
                Diperiksa oleh
                <strong>
                    {{ $pengumpulan->reviewer->name }}
                </strong>

                @if ($pengumpulan->reviewed_at)
                pada
                {{ $pengumpulan->reviewed_at->translatedFormat('d F Y, H:i') }}
                WITA
                @endif
            </p>

        </div>

        @endif

    </div>

</x-simaga-layout>