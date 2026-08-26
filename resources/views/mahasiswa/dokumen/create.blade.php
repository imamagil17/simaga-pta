<x-simaga-layout>

    <x-slot:title>Upload Dokumen - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Upload Dokumen</x-slot:headerTitle>

    <div class="mx-auto max-w-3xl space-y-6">

        <div>

            <a
                href="{{ route('mahasiswa.dokumen.index') }}"
                class="text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Dokumen
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

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="mb-6">

                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                    Upload Dokumen
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Pastikan dokumen yang diunggah benar dan dapat dibaca dengan jelas.
                </p>

            </div>

            <form
                method="POST"
                action="{{ route('mahasiswa.dokumen.store') }}"
                enctype="multipart/form-data"
                class="space-y-6">

                @csrf

                {{-- Jenis --}}
                <div>

                    <label
                        for="jenis_dokumen"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Jenis Dokumen
                        <span class="text-rose-500">*</span>
                    </label>

                    <select
                        id="jenis_dokumen"
                        name="jenis_dokumen"
                        required
                        class="mt-2 block w-full rounded-xl border-gray-300 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                        <option value="">
                            Pilih jenis dokumen
                        </option>

                        <option value="ktm" @selected(old('jenis_dokumen')==='ktm' )>
                            KTM
                        </option>

                        <option value="ktp" @selected(old('jenis_dokumen')==='ktp' )>
                            KTP
                        </option>

                        <option value="cv" @selected(old('jenis_dokumen')==='cv' )>
                            CV
                        </option>

                        <option value="surat_pengantar" @selected(old('jenis_dokumen')==='surat_pengantar' )>
                            Surat Pengantar
                        </option>

                        <option value="surat_pernyataan" @selected(old('jenis_dokumen')==='surat_pernyataan' )>
                            Surat Pernyataan
                        </option>

                        <option value="surat_penempatan" @selected(old('jenis_dokumen')==='surat_penempatan' )>
                            Surat Penempatan
                        </option>

                        <option value="surat_selesai_magang" @selected(old('jenis_dokumen')==='surat_selesai_magang' )>
                            Surat Selesai Magang
                        </option>

                        <option value="laporan_magang" @selected(old('jenis_dokumen')==='laporan_magang' )>
                            Laporan Magang
                        </option>

                        <option value="lampiran_laporan" @selected(old('jenis_dokumen')==='lampiran_laporan' )>
                            Lampiran Laporan
                        </option>

                        <option value="dokumen_pendukung" @selected(old('jenis_dokumen')==='dokumen_pendukung' )>
                            Dokumen Pendukung
                        </option>

                        <option value="dokumen_lainnya" @selected(old('jenis_dokumen')==='dokumen_lainnya' )>
                            Dokumen Lainnya
                        </option>

                    </select>

                </div>

                {{-- Nama --}}
                <div>

                    <label
                        for="nama_dokumen"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        Nama Dokumen
                        <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="text"
                        id="nama_dokumen"
                        name="nama_dokumen"
                        value="{{ old('nama_dokumen') }}"
                        required
                        maxlength="150"
                        placeholder="Contoh: Surat Pengantar Magang"
                        class="mt-2 block w-full rounded-xl border-gray-300 px-4 py-3 text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100">

                </div>

                {{-- File --}}
                <div>

                    <label
                        for="file"
                        class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                        File
                        <span class="text-rose-500">*</span>
                    </label>

                    <input
                        type="file"
                        id="file"
                        name="file"
                        required
                        accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png"
                        class="mt-2 block w-full rounded-xl border border-gray-300 bg-white text-sm dark:border-gray-600 dark:bg-gray-900 dark:text-gray-300">

                    <p class="mt-2 text-xs text-gray-500">
                        Format: PDF, Word, Excel, PowerPoint, JPG, JPEG, PNG. Maksimal 10 MB.
                    </p>

                </div>

                {{-- Info --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">

                    <p class="text-sm text-amber-800">
                        Dokumen akan disimpan secara privat dan hanya dapat diakses oleh pengguna yang memiliki hak akses.
                    </p>

                </div>

                <div class="flex justify-end gap-3">

                    <a
                        href="{{ route('mahasiswa.dokumen.index') }}"
                        class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700">
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                        Upload Dokumen
                    </button>

                </div>

            </form>

        </div>

    </div>

</x-simaga-layout>
