<x-simaga-layout>

    <x-slot:title>Pengaturan - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Pengaturan</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        {{-- Success --}}
        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4">

            <p class="text-sm font-semibold text-emerald-800">
                {{ session('success') }}
            </p>

        </div>

        @endif

        {{-- Errors --}}
        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4">

            <ul class="space-y-1 text-sm text-rose-800">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Sistem
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900">
                Pengaturan SIMAGA
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Atur identitas instansi, jam kerja, hari kerja, hari libur,
                dan bobot penilaian.
            </p>

        </div>

        {{-- ============================================================
             FORM UTAMA
        ============================================================= --}}
        <form
            method="POST"
            action="{{ route('admin.settings.update') }}"
            class="space-y-6">

            @csrf
            @method('PUT')

            {{-- PROFIL --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    01
                </p>

                <h3 class="mt-1 text-lg font-bold text-gray-900">
                    Profil Instansi
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Informasi dasar instansi.
                </p>

                <div class="mt-6 space-y-5">

                    <div>

                        <label
                            for="nama_instansi"
                            class="block text-sm font-semibold text-gray-700">
                            Nama Instansi
                        </label>

                        <input
                            type="text"
                            id="nama_instansi"
                            name="nama_instansi"
                            value="{{ old('nama_instansi', $settings['nama_instansi']) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300"
                            required>

                    </div>

                    <div>

                        <label
                            for="alamat_instansi"
                            class="block text-sm font-semibold text-gray-700">
                            Alamat
                        </label>

                        <textarea
                            id="alamat_instansi"
                            name="alamat_instansi"
                            rows="3"
                            class="mt-2 block w-full rounded-xl border-gray-300"
                            required>{{ old('alamat_instansi', $settings['alamat_instansi']) }}</textarea>

                    </div>

                </div>

            </div>

            {{-- JAM KERJA --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    02
                </p>

                <h3 class="mt-1 text-lg font-bold text-gray-900">
                    Jam Kerja
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Waktu standar kehadiran mahasiswa.
                </p>

                <div class="mt-6 grid grid-cols-1 gap-5 md:grid-cols-3">

                    <div>

                        <label
                            for="jam_masuk"
                            class="block text-sm font-semibold text-gray-700">
                            Jam Masuk
                        </label>

                        <input
                            type="time"
                            id="jam_masuk"
                            name="jam_masuk"
                            value="{{ old('jam_masuk', $settings['jam_masuk']) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300"
                            required>

                    </div>

                    <div>

                        <label
                            for="jam_pulang_senin_kamis"
                            class="block text-sm font-semibold text-gray-700">
                            Pulang Senin-Kamis
                        </label>

                        <input
                            type="time"
                            id="jam_pulang_senin_kamis"
                            name="jam_pulang_senin_kamis"
                            value="{{ old('jam_pulang_senin_kamis', $settings['jam_pulang_senin_kamis']) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300"
                            required>

                    </div>

                    <div>

                        <label
                            for="jam_pulang_jumat"
                            class="block text-sm font-semibold text-gray-700">
                            Pulang Jumat
                        </label>

                        <input
                            type="time"
                            id="jam_pulang_jumat"
                            name="jam_pulang_jumat"
                            value="{{ old('jam_pulang_jumat', $settings['jam_pulang_jumat']) }}"
                            class="mt-2 block w-full rounded-xl border-gray-300"
                            required>

                    </div>

                </div>

            </div>

            {{-- HARI KERJA --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    03
                </p>

                <h3 class="mt-1 text-lg font-bold text-gray-900">
                    Hari Kerja
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Centang hari yang merupakan hari masuk.
                    Hari yang tidak dicentang dianggap libur.
                </p>

                @php
                $daftarHari = [
                'senin' => 'Senin',
                'selasa' => 'Selasa',
                'rabu' => 'Rabu',
                'kamis' => 'Kamis',
                'jumat' => 'Jumat',
                'sabtu' => 'Sabtu',
                'minggu' => 'Minggu',
                ];
                @endphp

                <div class="mt-6 space-y-3">

                    @foreach ($daftarHari as $key => $label)

                    @php
                    $settingKey = 'hari_kerja_' . $key;

                    $aktif = old(
                    $settingKey,
                    $settings[$settingKey]
                    );
                    @endphp

                    <label
                        class="flex cursor-pointer items-center justify-between rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 transition hover:bg-gray-100">

                        <div class="flex items-center gap-3">

                            <input
                                type="checkbox"
                                name="{{ $settingKey }}"
                                value="1"
                                @checked($aktif)
                                class="h-5 w-5 rounded border-gray-300 text-emerald-700 focus:ring-emerald-600">

                            <div>

                                <p class="font-semibold text-gray-900">
                                    {{ $label }}
                                </p>

                                @if ($aktif)

                                <p class="text-xs text-emerald-600">
                                    Hari masuk
                                </p>

                                @else

                                <p class="text-xs text-gray-500">
                                    Hari libur
                                </p>

                                @endif

                            </div>

                        </div>

                        @if ($aktif)

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-bold text-emerald-800">

                            <span class="flex h-4 w-4 items-center justify-center rounded-full bg-emerald-600 text-white">
                                ✓
                            </span>

                            Masuk

                        </span>

                        @else

                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-100 px-3 py-1.5 text-xs font-bold text-rose-800">

                            <span class="flex h-4 w-4 items-center justify-center rounded-full bg-rose-600 text-white">
                                ×
                            </span>

                            Libur

                        </span>

                        @endif

                    </label>

                    @endforeach

                </div>

            </div>

            {{-- BOBOT --}}
            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                    04
                </p>

                <h3 class="mt-1 text-lg font-bold text-gray-900">
                    Bobot Penilaian
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Total seluruh bobot harus tepat 100%.
                </p>

                <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">

                    @foreach ([
                    'bobot_kedisiplinan' => 'Kedisiplinan',
                    'bobot_kehadiran' => 'Kehadiran',
                    'bobot_kinerja' => 'Kinerja',
                    'bobot_kompetensi' => 'Kompetensi',
                    'bobot_sikap' => 'Sikap',
                    ] as $key => $label)

                    <div>

                        <label
                            for="{{ $key }}"
                            class="block text-sm font-semibold text-gray-700">
                            {{ $label }}
                        </label>

                        <div class="relative mt-2">

                            <input
                                type="number"
                                id="{{ $key }}"
                                name="{{ $key }}"
                                min="0"
                                max="100"
                                value="{{ old($key, $settings[$key]) }}"
                                class="block w-full rounded-xl border-gray-300 pr-10"
                                required>

                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-sm text-gray-400">
                                %
                            </span>

                        </div>

                    </div>

                    @endforeach

                </div>

                <div class="mt-5 rounded-xl bg-gray-50 p-4">

                    <p class="text-sm text-gray-600">
                        Total bobot harus tepat
                        <strong>100%</strong>.
                    </p>

                </div>

            </div>

            {{-- SIMPAN FORM UTAMA --}}
            <div class="flex justify-end">

                <button
                    type="submit"
                    class="rounded-xl bg-emerald-700 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800">
                    Simpan Pengaturan
                </button>

            </div>

        </form>

        {{-- ============================================================
             FORM HARI LIBUR — FORM TERPISAH
        ============================================================= --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                05
            </p>

            <h3 class="mt-1 text-lg font-bold text-gray-900">
                Hari Libur Khusus
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Tambahkan tanggal merah atau hari libur khusus.
            </p>

            {{-- FORM TAMBAH LIBUR --}}
            <form
                method="POST"
                action="{{ route('admin.settings.hari-libur.store') }}"
                class="mt-6 grid grid-cols-1 gap-4 rounded-xl bg-gray-50 p-5 md:grid-cols-[180px_1fr_auto]">

                @csrf

                <div>

                    <label
                        for="tanggal_libur"
                        class="block text-sm font-semibold text-gray-700">
                        Tanggal
                    </label>

                    <input
                        type="date"
                        id="tanggal_libur"
                        name="tanggal"
                        value="{{ old('tanggal') }}"
                        class="mt-2 block w-full rounded-xl border-gray-300"
                        required>

                </div>

                <div>

                    <label
                        for="nama_libur"
                        class="block text-sm font-semibold text-gray-700">
                        Keterangan
                    </label>

                    <input
                        type="text"
                        id="nama_libur"
                        name="nama"
                        value="{{ old('nama') }}"
                        placeholder="Contoh: Hari Kemerdekaan"
                        class="mt-2 block w-full rounded-xl border-gray-300"
                        required>

                </div>

                <div class="flex items-end">

                    <button
                        type="submit"
                        class="w-full rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800 md:w-auto">
                        Tambah Libur
                    </button>

                </div>

            </form>

            {{-- DAFTAR LIBUR --}}
            <div class="mt-5 overflow-hidden rounded-xl border border-gray-200">

                <div class="overflow-x-auto">

                    <table class="min-w-full divide-y divide-gray-200">

                        <thead class="bg-gray-50">

                            <tr>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Tanggal
                                </th>

                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-500">
                                    Keterangan
                                </th>

                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-500">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody class="divide-y divide-gray-100">

                            @forelse ($hariLiburs as $hariLibur)

                            <tr>

                                <td class="px-4 py-3 text-sm font-semibold text-gray-900">
                                    {{ $hariLibur->tanggal->translatedFormat('d F Y') }}
                                </td>

                                <td class="px-4 py-3 text-sm text-gray-700">
                                    {{ $hariLibur->nama }}
                                </td>

                                <td class="px-4 py-3 text-right">

                                    <form
                                        method="POST"
                                        action="{{ route('admin.settings.hari-libur.destroy', $hariLibur) }}"
                                        onsubmit="return confirm('Hapus hari libur ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="rounded-lg bg-rose-50 px-3 py-2 text-xs font-semibold text-rose-700 hover:bg-rose-100">
                                            Hapus
                                        </button>

                                    </form>

                                </td>

                            </tr>

                            @empty

                            <tr>

                                <td
                                    colspan="3"
                                    class="px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada hari libur khusus.
                                </td>

                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</x-simaga-layout>