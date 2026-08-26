<x-simaga-layout>

    <x-slot:title>Sertifikat Mahasiswa - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Sertifikat Mahasiswa</x-slot:headerTitle>

    <div class="mx-auto max-w-7xl space-y-6">

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

        {{-- Header --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                Administrasi
            </p>

            <h3 class="mt-1 text-xl font-bold text-gray-900">
                Pengajuan Sertifikat
            </h3>

            <p class="mt-1 text-sm text-gray-500">
                Periksa dan proses pengajuan sertifikat dari mentor.
            </p>

        </div>

        {{-- Rekap --}}
        <div class="grid grid-cols-2 gap-4 sm:grid-cols-4">

            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">

                <p class="text-xs font-semibold uppercase text-gray-500">
                    Total
                </p>

                <p class="mt-2 text-3xl font-bold text-gray-900">
                    {{ $rekap['total'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-5">

                <p class="text-xs font-semibold uppercase text-amber-600">
                    Menunggu
                </p>

                <p class="mt-2 text-3xl font-bold text-amber-800">
                    {{ $rekap['pending'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-5">

                <p class="text-xs font-semibold uppercase text-emerald-600">
                    Disetujui
                </p>

                <p class="mt-2 text-3xl font-bold text-emerald-800">
                    {{ $rekap['approved'] }}
                </p>

            </div>

            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-5">

                <p class="text-xs font-semibold uppercase text-rose-600">
                    Ditolak
                </p>

                <p class="mt-2 text-3xl font-bold text-rose-800">
                    {{ $rekap['rejected'] }}
                </p>

            </div>

        </div>

        {{-- Filter --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <form
                method="GET"
                action="{{ route('admin.sertifikat.index') }}"
                class="grid grid-cols-1 gap-4 md:grid-cols-3">

                <div>

                    <label
                        for="search"
                        class="block text-sm font-semibold text-gray-700">
                        Cari Mahasiswa
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="{{ $filters['search'] }}"
                        placeholder="Nama atau NIM..."
                        class="mt-2 block w-full rounded-xl border-gray-300 text-sm">

                </div>

                <div>

                    <label
                        for="status"
                        class="block text-sm font-semibold text-gray-700">
                        Status
                    </label>

                    <select
                        id="status"
                        name="status"
                        class="mt-2 block w-full rounded-xl border-gray-300 text-sm">

                        <option value="">
                            Semua Status
                        </option>

                        <option
                            value="pending"
                            @selected($filters['status']==='pending' )>
                            Menunggu
                        </option>

                        <option
                            value="approved"
                            @selected($filters['status']==='approved' )>
                            Disetujui
                        </option>

                        <option
                            value="rejected"
                            @selected($filters['status']==='rejected' )>
                            Ditolak
                        </option>

                    </select>

                </div>

                <div class="flex items-end gap-3">

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">
                        Filter
                    </button>

                    <a
                        href="{{ route('admin.sertifikat.index') }}"
                        class="rounded-xl border border-gray-300 bg-white px-5 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-50">
                        Reset
                    </a>

                </div>

            </form>

        </div>

        {{-- Table --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h3 class="font-bold text-gray-900">
                    Pengajuan Sertifikat
                </h3>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Mentor
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Periode
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Nomor Sertifikat
                            </th>

                            <th class="px-5 py-4 text-right text-xs font-semibold uppercase text-gray-500">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($sertifikats as $sertifikat)

                        <tr class="transition hover:bg-gray-50">

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $sertifikat->mahasiswa->user->name ?? '-' }}
                                </p>

                                <p class="mt-1 text-xs text-gray-500">
                                    {{ $sertifikat->mahasiswa->nim ?? '-' }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                {{ $sertifikat->mentor->user->name ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">
                                {{ $sertifikat->penempatan->periodeMagang->nama_periode ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-center">

                                @if ($sertifikat->status === 'pending')

                                <span class="inline-flex rounded-full bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-800">
                                    Menunggu
                                </span>

                                @elseif ($sertifikat->status === 'approved')

                                <span class="inline-flex rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                                    Disetujui
                                </span>

                                @else

                                <span class="inline-flex rounded-full bg-rose-100 px-3 py-1.5 text-xs font-semibold text-rose-800">
                                    Ditolak
                                </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-sm text-gray-700">

                                {{ $sertifikat->nomor_sertifikat ?? '-' }}

                            </td>

                            <td class="px-5 py-4 text-right">

                                <a
                                    href="{{ route('admin.sertifikat.show', $sertifikat) }}"
                                    class="inline-flex rounded-lg bg-emerald-700 px-3 py-2 text-xs font-semibold text-white hover:bg-emerald-800">
                                    Detail
                                </a>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-6 py-16 text-center text-sm text-gray-500">
                                Belum ada pengajuan sertifikat.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-simaga-layout>