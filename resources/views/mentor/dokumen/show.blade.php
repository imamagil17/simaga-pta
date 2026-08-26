<x-simaga-layout>

    <x-slot:title>Detail Dokumen - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Dokumen</x-slot:headerTitle>

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

        {{-- Back --}}
        <div>

            <a
                href="{{ route('mentor.dokumen.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Dokumen
            </a>

        </div>

        {{-- Identitas mahasiswa --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mahasiswa
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-900">
                        {{ $dokumen->mahasiswa->user->name ?? '-' }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $dokumen->mahasiswa->nim ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mentor
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $dokumen->penempatan->mentor->user->name ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-semibold text-gray-900">
                        {{ $dokumen->penempatan->periodeMagang->nama_periode ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Detail dokumen --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Dokumen
                    </p>

                    <h3 class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $dokumen->nama_dokumen }}
                    </h3>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $dokumen->nama_file }}
                    </p>

                </div>

                @switch($dokumen->status)

                @case('uploaded')

                <span class="inline-flex w-fit rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                    Menunggu Verifikasi
                </span>

                @break

                @case('verified')

                <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                    Terverifikasi
                </span>

                @break

                @case('revision')

                <span class="inline-flex w-fit rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800">
                    Perlu Revisi
                </span>

                @break

                @endswitch

            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Jenis
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ str_replace('_', ' ', ucfirst($dokumen->jenis_dokumen)) }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Ukuran
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $dokumen->ukuran_file ? number_format($dokumen->ukuran_file / 1024, 1) . ' KB' : '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Diunggah
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $dokumen->created_at?->format('d-m-Y H:i') }}
                    </p>

                </div>

            </div>

            @if ($dokumen->catatan)

            <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">
                    Catatan
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-rose-800">
                    {{ $dokumen->catatan }}
                </p>

            </div>

            @endif

            <div class="mt-6 flex flex-wrap gap-3 border-t border-gray-100 pt-6">

                @if (
                in_array($dokumen->mime_type, [
                'application/pdf',
                'image/jpeg',
                'image/png',
                'image/webp'
                ], true)
                )

                <a
                    href="{{ route('mentor.dokumen.preview', $dokumen) }}"
                    target="_blank"
                    class="rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                    Preview Dokumen
                </a>

                @endif

                <a
                    href="{{ route('mentor.dokumen.download', $dokumen) }}"
                    class="rounded-xl bg-gray-800 px-4 py-2.5 text-sm font-semibold text-white hover:bg-gray-900">
                    Download Dokumen
                </a>

            </div>

        </div>

        {{-- Aksi --}}
        @if ($dokumen->status === 'uploaded')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Verifikasi --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

                <h3 class="font-bold text-emerald-900">
                    Verifikasi Dokumen
                </h3>

                <p class="mt-1 text-sm text-emerald-800">
                    Tandai dokumen sebagai valid dan sesuai.
                </p>

                <form
                    method="POST"
                    action="{{ route('mentor.dokumen.verify', $dokumen) }}"
                    class="mt-5 space-y-4">

                    @csrf

                    <div>

                        <label
                            for="catatan_verifikasi"
                            class="block text-sm font-semibold text-emerald-900">
                            Catatan
                        </label>

                        <textarea
                            id="catatan_verifikasi"
                            name="catatan"
                            rows="4"
                            placeholder="Opsional..."
                            class="mt-2 block w-full rounded-xl border-emerald-300 bg-white text-sm">{{ old('catatan') }}</textarea>

                    </div>

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                        Verifikasi Dokumen
                    </button>

                </form>

            </div>

            {{-- Revisi --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6">

                <h3 class="font-bold text-rose-900">
                    Minta Revisi
                </h3>

                <p class="mt-1 text-sm text-rose-800">
                    Jelaskan bagian yang harus diperbaiki mahasiswa.
                </p>

                <form
                    method="POST"
                    action="{{ route('mentor.dokumen.revision', $dokumen) }}"
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
                            name="catatan"
                            rows="5"
                            required
                            minlength="5"
                            placeholder="Contoh: File kurang jelas, mohon unggah ulang..."
                            class="mt-2 block w-full rounded-xl border-rose-300 bg-white text-sm">{{ old('catatan') }}</textarea>

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

        {{-- Informasi verifikasi --}}
        @if ($dokumen->verifier)

        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Pemeriksaan
            </p>

            <p class="mt-2 text-sm text-gray-700">

                Diperiksa oleh
                <strong>
                    {{ $dokumen->verifier->name }}
                </strong>

                @if ($dokumen->verified_at)

                pada
                {{ $dokumen->verified_at->format('d-m-Y H:i') }}

                @endif

            </p>

        </div>

        @endif

    </div>

</x-simaga-layout>