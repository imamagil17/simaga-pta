<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');

    $now = \Carbon\Carbon::now();
    $mulai = \Carbon\Carbon::parse($tugas->tanggal_mulai);
    $deadline = \Carbon\Carbon::parse($tugas->tanggal_deadline);

    $belumMulai = $now->lt($mulai);
    $terlambat = $now->gt($deadline);
    @endphp

    <x-slot:title>Detail Tugas - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Tugas</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">

            <p class="text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </p>

        </div>

        @endif

        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">

            <ul class="space-y-1 text-sm text-rose-800">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        <div>

            <a
                href="{{ route('mahasiswa.tugas.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Tugas
            </a>

        </div>

        {{-- Detail Tugas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                        Tugas dari Mentor
                    </p>

                    <h3 class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $tugas->judul }}
                    </h3>

                    <p class="mt-2 text-sm text-gray-500">
                        Mentor:
                        {{ $tugas->penempatan->mentor->user->name }}
                    </p>

                </div>

                @if ($tugas->status === 'closed')

                <span class="inline-flex w-fit rounded-full bg-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700">
                    Tugas Ditutup
                </span>

                @elseif ($pengumpulan?->status === 'submitted')

                <span class="inline-flex w-fit rounded-full bg-blue-100 px-3 py-1.5 text-xs font-semibold text-blue-800">
                    Sudah Dikumpulkan
                </span>

                @elseif ($pengumpulan?->status === 'revision')

                <span class="inline-flex w-fit rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800">
                    Perlu Revisi
                </span>

                @elseif ($belumMulai)

                <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                    Belum Dimulai
                </span>

                @elseif ($terlambat)

                <span class="inline-flex w-fit rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800">
                    Deadline Lewat
                </span>

                @else

                <span class="inline-flex w-fit rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                    Sedang Berlangsung
                </span>

                @endif

            </div>

            <div class="mt-6">

                <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                    Instruksi Tugas
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-700">
                    {{ $tugas->deskripsi }}
                </p>

            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Tanggal Mulai
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $mulai->translatedFormat('d F Y, H:i') }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Deadline
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $deadline->translatedFormat('d F Y, H:i') }}
                    </p>

                </div>

            </div>

            @if ($tugas->file_tugas)

            <div class="mt-6">

                <a
                    href="{{ asset('storage/' . $tugas->file_tugas) }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Buka File Tugas
                </a>

            </div>

            @endif

        </div>

        {{-- Catatan mentor --}}
        @if ($pengumpulan?->catatan_mentor)

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6">

            <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">
                Catatan Mentor
            </p>

            <p class="mt-2 whitespace-pre-line text-sm leading-7 text-rose-800">
                {{ $pengumpulan->catatan_mentor }}
            </p>

        </div>

        @endif

        {{-- Nilai --}}
        @if ($pengumpulan?->status === 'reviewed')

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Hasil Pemeriksaan
            </p>

            <p class="mt-2 text-3xl font-bold text-emerald-800">
                {{ $pengumpulan->nilai ?? '-' }}
            </p>

        </div>

        @endif

        {{-- Form --}}
        @if (
        $tugas->status === 'published' &&
        ! $belumMulai &&
        ! $terlambat &&
        (
        ! $pengumpulan ||
        in_array($pengumpulan->status, ['draft', 'revision'], true)
        )
        )

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div>

                <h3 class="text-lg font-bold text-gray-900">
                    {{ $pengumpulan?->status === 'revision'
                            ? 'Perbaiki Jawaban'
                            : 'Kerjakan Tugas' }}
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Isi jawaban atau unggah file jawaban Anda.
                </p>

            </div>

            <form
                method="POST"
                action="{{ route('mahasiswa.tugas.store', $tugas) }}"
                enctype="multipart/form-data"
                class="mt-6 space-y-5">

                @csrf

                <div>

                    <label
                        for="jawaban"
                        class="block text-sm font-semibold text-gray-700">
                        Jawaban
                    </label>

                    <textarea
                        id="jawaban"
                        name="jawaban"
                        rows="8"
                        placeholder="Tulis jawaban Anda..."
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm">{{ old('jawaban', $pengumpulan?->jawaban) }}</textarea>

                </div>

                <div>

                    <label
                        for="file_jawaban"
                        class="block text-sm font-semibold text-gray-700">
                        File Jawaban
                    </label>

                    <input
                        type="file"
                        id="file_jawaban"
                        name="file_jawaban"
                        class="mt-2 block w-full rounded-xl border border-gray-300 bg-white text-sm">

                    <p class="mt-2 text-xs text-gray-500">
                        Maksimal 10 MB.
                    </p>

                    @if ($pengumpulan?->file_jawaban)

                    <p class="mt-2 text-xs text-gray-500">
                        File jawaban saat ini sudah tersimpan.
                    </p>

                    @endif

                </div>

                <button
                    type="submit"
                    class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                    Simpan Jawaban
                </button>

            </form>

        </div>

        @endif

        {{-- Submit --}}
        @if (
        $tugas->status === 'published' &&
        ! $belumMulai &&
        ! $terlambat &&
        $pengumpulan &&
        in_array($pengumpulan->status, ['draft', 'revision'], true)
        )

        <div class="rounded-2xl border border-blue-200 bg-blue-50 p-6">

            <h3 class="font-bold text-blue-900">
                Kirim Tugas
            </h3>

            <p class="mt-1 text-sm text-blue-800">
                Pastikan jawaban sudah benar sebelum dikirim ke mentor.
            </p>

            <form
                method="POST"
                action="{{ route('mahasiswa.tugas.submit', $tugas) }}"
                class="mt-5">

                @csrf

                <button
                    type="submit"
                    class="rounded-xl bg-blue-700 px-5 py-3 text-sm font-semibold text-white hover:bg-blue-800">
                    Submit Tugas
                </button>

            </form>

        </div>

        @endif

    </div>

</x-simaga-layout>