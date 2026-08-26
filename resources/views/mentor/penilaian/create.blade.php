<x-simaga-layout>

    <x-slot:title>Isi Penilaian - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Isi Penilaian</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        <div>

            <a
                href="{{ route('mentor.penilaian.index') }}"
                class="text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Penilaian
            </a>

        </div>

        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5">

            <ul class="space-y-1 text-sm text-rose-800">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        {{-- Mahasiswa --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Mahasiswa
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900">
                {{ $penempatan->mahasiswa->user->name }}
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                {{ $penempatan->mahasiswa->nim }}
            </p>

            <p class="mt-3 text-sm text-gray-600">
                {{ $penempatan->periodeMagang->nama_periode }}
            </p>

        </div>

        {{-- Form --}}
        <form
            method="POST"
            action="{{ route('mentor.penilaian.store', $penempatan->mahasiswa) }}"
            class="space-y-6">

            @csrf

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <h3 class="text-lg font-bold text-gray-900">
                    Komponen Penilaian
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Masukkan nilai 0 sampai 100 untuk setiap aspek.
                </p>

                <div class="mt-6 space-y-5">

                    {{-- Kedisiplinan --}}
                    <div>

                        <label
                            for="nilai_kedisiplinan"
                            class="block text-sm font-semibold text-gray-700">
                            Kedisiplinan
                            <span class="font-normal text-gray-500">
                                (20%)
                            </span>
                        </label>

                        <input
                            type="number"
                            id="nilai_kedisiplinan"
                            name="nilai_kedisiplinan"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                            value="{{ old('nilai_kedisiplinan', $penilaian?->nilai_kedisiplinan) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300">

                    </div>

                    {{-- Kehadiran --}}
                    <div>

                        <label
                            for="nilai_kehadiran"
                            class="block text-sm font-semibold text-gray-700">
                            Kehadiran
                            <span class="font-normal text-gray-500">
                                (20%)
                            </span>
                        </label>

                        <input
                            type="number"
                            id="nilai_kehadiran"
                            name="nilai_kehadiran"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                            value="{{ old('nilai_kehadiran', $penilaian?->nilai_kehadiran) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300">

                    </div>

                    {{-- Kinerja --}}
                    <div>

                        <label
                            for="nilai_kinerja"
                            class="block text-sm font-semibold text-gray-700">
                            Kinerja / Tanggung Jawab
                            <span class="font-normal text-gray-500">
                                (20%)
                            </span>
                        </label>

                        <input
                            type="number"
                            id="nilai_kinerja"
                            name="nilai_kinerja"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                            value="{{ old('nilai_kinerja', $penilaian?->nilai_kinerja) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300">

                    </div>

                    {{-- Kompetensi --}}
                    <div>

                        <label
                            for="nilai_kompetensi"
                            class="block text-sm font-semibold text-gray-700">
                            Kompetensi
                            <span class="font-normal text-gray-500">
                                (20%)
                            </span>
                        </label>

                        <input
                            type="number"
                            id="nilai_kompetensi"
                            name="nilai_kompetensi"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                            value="{{ old('nilai_kompetensi', $penilaian?->nilai_kompetensi) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300">

                    </div>

                    {{-- Sikap --}}
                    <div>

                        <label
                            for="nilai_sikap"
                            class="block text-sm font-semibold text-gray-700">
                            Sikap / Etika
                            <span class="font-normal text-gray-500">
                                (20%)
                            </span>
                        </label>

                        <input
                            type="number"
                            id="nilai_sikap"
                            name="nilai_sikap"
                            min="0"
                            max="100"
                            step="0.01"
                            required
                            value="{{ old('nilai_sikap', $penilaian?->nilai_sikap) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300">

                    </div>

                    {{-- Catatan --}}
                    <div>

                        <label
                            for="catatan"
                            class="block text-sm font-semibold text-gray-700">
                            Catatan Mentor
                        </label>

                        <textarea
                            id="catatan"
                            name="catatan"
                            rows="5"
                            maxlength="5000"
                            placeholder="Berikan catatan atau evaluasi mahasiswa..."
                            class="mt-2 block w-full rounded-xl border-gray-300">{{ old('catatan', $penilaian?->catatan) }}</textarea>

                    </div>

                </div>

            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                <p class="text-sm text-emerald-800">
                    Nilai akhir akan dihitung otomatis berdasarkan bobot masing-masing aspek.
                </p>

            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('mentor.penilaian.index') }}"
                    class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700">
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                    Simpan Draft
                </button>

            </div>

        </form>

    </div>

</x-simaga-layout>