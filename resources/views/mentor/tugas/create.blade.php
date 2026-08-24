<x-simaga-layout>

    <x-slot:title>Buat Tugas - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Buat Tugas</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        <div>

            <a
                href="{{ route('mentor.tugas.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 dark:text-gray-400 dark:hover:text-emerald-400">
                ← Kembali ke Tugas
            </a>

        </div>

        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5 dark:border-rose-900/40 dark:bg-rose-900/20">

            <ul class="space-y-1 text-sm text-rose-800 dark:text-rose-300">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        @if ($penempatans->isEmpty())

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5 dark:border-amber-900/40 dark:bg-amber-900/20">

            <p class="font-semibold text-amber-900 dark:text-amber-200">
                Belum ada mahasiswa bimbingan aktif.
            </p>

            <p class="mt-1 text-sm text-amber-800 dark:text-amber-300">
                Anda belum dapat membuat tugas karena belum memiliki mahasiswa bimbingan yang aktif.
            </p>

        </div>

        @else

        <form
            method="POST"
            action="{{ route('mentor.tugas.store') }}"
            enctype="multipart/form-data"
            class="space-y-6">

            @csrf

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="space-y-6">

                    <div>

                        <label
                            for="penempatan_id"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Mahasiswa Bimbingan
                            <span class="text-rose-500">*</span>
                        </label>

                        <select
                            id="penempatan_id"
                            name="penempatan_id"
                            required
                            class="mt-2 block w-full rounded-xl border-gray-300 bg-white text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                            <option value="">
                                Pilih mahasiswa
                            </option>

                            @foreach ($penempatans as $penempatan)

                            <option
                                value="{{ $penempatan->id }}"
                                @selected(old('penempatan_id')==$penempatan->id)
                                >
                                {{ $penempatan->mahasiswa->user->name }}
                                — {{ $penempatan->mahasiswa->nim }}
                            </option>

                            @endforeach

                        </select>

                    </div>

                    <div>

                        <label
                            for="judul"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Judul Tugas
                            <span class="text-rose-500">*</span>
                        </label>

                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            value="{{ old('judul') }}"
                            maxlength="150"
                            required
                            placeholder="Contoh: Membuat Modul Absensi"
                            class="mt-2 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                    </div>

                    <div>

                        <label
                            for="deskripsi"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Deskripsi / Instruksi
                            <span class="text-rose-500">*</span>
                        </label>

                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="6"
                            required
                            placeholder="Tuliskan instruksi tugas secara jelas..."
                            class="mt-2 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('deskripsi') }}</textarea>

                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div>

                            <label
                                for="tanggal_mulai"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Tanggal Mulai
                                <span class="text-rose-500">*</span>
                            </label>

                            <input
                                type="datetime-local"
                                id="tanggal_mulai"
                                name="tanggal_mulai"
                                value="{{ old('tanggal_mulai') }}"
                                required
                                class="mt-2 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                        </div>

                        <div>

                            <label
                                for="tanggal_deadline"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Deadline
                                <span class="text-rose-500">*</span>
                            </label>

                            <input
                                type="datetime-local"
                                id="tanggal_deadline"
                                name="tanggal_deadline"
                                value="{{ old('tanggal_deadline') }}"
                                required
                                class="mt-2 block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                        </div>

                    </div>

                    <div>

                        <label
                            for="file_tugas"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            File Tugas
                        </label>

                        <input
                            type="file"
                            id="file_tugas"
                            name="file_tugas"
                            class="mt-2 block w-full rounded-xl border border-gray-300 bg-white text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300">

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Maksimal 10 MB. PDF, Word, Excel, PowerPoint, JPG, JPEG, PNG.
                        </p>

                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('mentor.tugas.index') }}"
                    class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                    Simpan Draft
                </button>

            </div>

        </form>

        @endif

    </div>

</x-simaga-layout>