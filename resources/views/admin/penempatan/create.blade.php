<x-simaga-layout>
    <x-slot:title>Tambah Penempatan - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Tambah Penempatan</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="flex items-start gap-4">

                <a
                    href="{{ route('admin.penempatans.index') }}"
                    class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border border-gray-300 bg-white text-gray-600 hover:bg-gray-50 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7-7"
                        />
                    </svg>
                </a>

                <div>
                    <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                        Tambah Penempatan Mahasiswa
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Tentukan mahasiswa, periode, dan mentor pembimbing.
                    </p>
                </div>

            </div>

        </div>

        <form
            method="POST"
            action="{{ route('admin.penempatans.store') }}"
            class="space-y-6"
        >
            @csrf

            @if ($errors->any())
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">
                    <p class="text-sm font-semibold text-rose-800 dark:text-rose-200">
                        Periksa kembali data yang dimasukkan.
                    </p>

                    <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-700 dark:text-rose-300">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Penempatan Utama --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Data Penempatan
                    </h4>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Pilih mahasiswa dan periode terlebih dahulu.
                    </p>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                    {{-- Mahasiswa --}}
                    <div>
                        <label for="mahasiswa_id" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Mahasiswa
                        </label>

                        <select
                            id="mahasiswa_id"
                            name="mahasiswa_id"
                            required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Pilih Mahasiswa</option>

                            @foreach ($mahasiswas as $mahasiswa)
                                <option
                                    value="{{ $mahasiswa->id }}"
                                    @selected(old('mahasiswa_id') == $mahasiswa->id)
                                >
                                    {{ $mahasiswa->user->name }} — {{ $mahasiswa->nim }}
                                </option>
                            @endforeach
                        </select>

                        @error('mahasiswa_id')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Periode --}}
                    <div>
                        <label for="periode_magang_id" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Periode Magang
                        </label>

                        <select
                            id="periode_magang_id"
                            name="periode_magang_id"
                            required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Pilih Periode</option>

                            @foreach ($periodes as $periode)
                                <option
                                    value="{{ $periode->id }}"
                                    @selected(old('periode_magang_id') == $periode->id)
                                >
                                    {{ $periode->nama_periode }}
                                    — {{ $periode->kode_periode }}
                                </option>
                            @endforeach
                        </select>

                        @error('periode_magang_id')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Mentor --}}
                    <div class="md:col-span-2">
                        <label for="mentor_id" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Mentor Pembimbing
                        </label>

                        <select
                            id="mentor_id"
                            name="mentor_id"
                            required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">
                                Pilih periode terlebih dahulu
                            </option>

                            @foreach ($mentorPeriodes as $periodeId => $items)

                                @foreach ($items as $mentorPeriode)

                                    <option
                                        value="{{ $mentorPeriode->mentor_id }}"
                                        data-periode="{{ $periodeId }}"
                                        @selected(
                                            old('mentor_id') == $mentorPeriode->mentor_id &&
                                            old('periode_magang_id') == $periodeId
                                        )
                                    >
                                        {{ $mentorPeriode->mentor->user->name }}
                                        @if ($mentorPeriode->mentor->jabatan)
                                            — {{ $mentorPeriode->mentor->jabatan }}
                                        @endif
                                    </option>

                                @endforeach

                            @endforeach

                        </select>

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Hanya mentor yang aktif dan terdaftar pada periode terpilih yang tersedia.
                        </p>

                        @error('mentor_id')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- Waktu --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="border-b border-gray-200 pb-4 dark:border-gray-700">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                        Masa Penempatan
                    </h4>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label for="tanggal_mulai" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Mulai
                        </label>

                        <input
                            type="date"
                            id="tanggal_mulai"
                            name="tanggal_mulai"
                            value="{{ old('tanggal_mulai') }}"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('tanggal_mulai')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="tanggal_selesai" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Tanggal Selesai
                        </label>

                        <input
                            type="date"
                            id="tanggal_selesai"
                            name="tanggal_selesai"
                            value="{{ old('tanggal_selesai') }}"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >

                        @error('tanggal_selesai')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- Status --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

                    <div>
                        <label for="status" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="active" @selected(old('status', 'active') === 'active')}>
                                Aktif
                            </option>

                            <option value="inactive" @selected(old('status') === 'inactive')}>
                                Tidak Aktif
                            </option>
                        </select>

                        @error('status')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="keterangan" class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Keterangan
                        </label>

                        <textarea
                            id="keterangan"
                            name="keterangan"
                            rows="3"
                            class="w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 focus:border-emerald-500 focus:ring-emerald-500 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                        >{{ old('keterangan') }}</textarea>

                        @error('keterangan')
                            <p class="mt-1 text-xs text-rose-600 dark:text-rose-400">
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                </div>

            </div>

            {{-- Action --}}
            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">

                <a
                    href="{{ route('admin.penempatans.index') }}"
                    class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800"
                >
                    Simpan Penempatan
                </button>

            </div>

        </form>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const periodeSelect = document.getElementById('periode_magang_id');
            const mentorSelect = document.getElementById('mentor_id');

            function filterMentors() {
                const periodeId = periodeSelect.value;
                const currentMentor = mentorSelect.value;

                let hasOption = false;

                Array.from(mentorSelect.options).forEach((option, index) => {
                    if (index === 0) {
                        return;
                    }

                    const match = option.dataset.periode === periodeId;

                    option.hidden = !match;

                    if (match) {
                        hasOption = true;
                    }
                });

                if (!periodeId) {
                    mentorSelect.value = '';
                    mentorSelect.options[0].textContent = 'Pilih periode terlebih dahulu';
                    return;
                }

                mentorSelect.options[0].textContent = hasOption
                    ? 'Pilih Mentor'
                    : 'Belum ada mentor aktif pada periode ini';

                const selectedOption = Array.from(mentorSelect.options)
                    .find(option =>
                        option.value === currentMentor &&
                        option.dataset.periode === periodeId
                    );

                if (!selectedOption) {
                    mentorSelect.value = '';
                }
            }

            periodeSelect.addEventListener('change', function () {
                mentorSelect.value = '';
                filterMentors();
            });

            filterMentors();
        });
    </script>

</x-simaga-layout>