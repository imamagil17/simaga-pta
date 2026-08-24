<x-simaga-layout>

    <x-slot:title>Edit Tugas - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Edit Tugas</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        <div>

            <a
                href="{{ route('mentor.tugas.show', $tugas) }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700 dark:text-gray-400 dark:hover:text-emerald-400">
                ← Kembali ke Detail
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

        <form
            method="POST"
            action="{{ route('mentor.tugas.update', $tugas) }}"
            enctype="multipart/form-data"
            class="space-y-6">

            @csrf
            @method('PUT')

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="space-y-6">

                    <div>

                        <label
                            for="penempatan_id"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Mahasiswa Bimbingan
                        </label>

                        <select
                            id="penempatan_id"
                            name="penempatan_id"
                            required
                            class="mt-2 block w-full rounded-xl border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                            @foreach ($penempatans as $penempatan)

                            <option
                                value="{{ $penempatan->id }}"
                                @selected(old('penempatan_id', $tugas->penempatan_id) == $penempatan->id)
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
                        </label>

                        <input
                            type="text"
                            id="judul"
                            name="judul"
                            value="{{ old('judul', $tugas->judul) }}"
                            maxlength="150"
                            required
                            class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                    </div>

                    <div>

                        <label
                            for="deskripsi"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Deskripsi / Instruksi
                        </label>

                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="6"
                            required
                            class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>

                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        <div>

                            <label
                                for="tanggal_mulai"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Tanggal Mulai
                            </label>

                            <input
                                type="datetime-local"
                                id="tanggal_mulai"
                                name="tanggal_mulai"
                                value="{{ old('tanggal_mulai', $tugas->tanggal_mulai->format('Y-m-d\TH:i')) }}"
                                required
                                class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                        </div>

                        <div>

                            <label
                                for="tanggal_deadline"
                                class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Deadline
                            </label>

                            <input
                                type="datetime-local"
                                id="tanggal_deadline"
                                name="tanggal_deadline"
                                value="{{ old('tanggal_deadline', $tugas->tanggal_deadline->format('Y-m-d\TH:i')) }}"
                                required
                                class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                        </div>

                    </div>

                    <div>

                        <label
                            for="file_tugas"
                            class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Ganti File Tugas
                        </label>

                        <input
                            type="file"
                            id="file_tugas"
                            name="file_tugas"
                            class="mt-2 block w-full rounded-xl border border-gray-300 bg-white text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300">

                        @if ($tugas->file_tugas)

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            File tugas saat ini sudah tersedia.
                        </p>

                        @endif

                    </div>

                </div>

            </div>

            <div class="flex justify-end gap-3">

                <a
                    href="{{ route('mentor.tugas.show', $tugas) }}"
                    class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                    Batal
                </a>

                <button
                    type="submit"
                    class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                    Simpan Perubahan
                </button>

            </div>

        </form>

    </div>

</x-simaga-layout>