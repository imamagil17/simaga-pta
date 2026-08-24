<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
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
                href="{{ route('mentor.tugas.index') }}"
                class="text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Tugas
            </a>

        </div>

        {{-- Identitas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Mahasiswa
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $tugas->penempatan->mahasiswa->user->name }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $tugas->penempatan->mahasiswa->nim }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Mentor
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $tugas->penempatan->mentor->user->name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $tugas->penempatan->periodeMagang->nama_periode }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Detail --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Judul Tugas
                    </p>

                    <h3 class="mt-1 text-2xl font-bold text-gray-900">
                        {{ $tugas->judul }}
                    </h3>

                </div>

                @switch($tugas->status)

                @case('draft')

                <span class="inline-flex w-fit rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-700">
                    Draft
                </span>

                @break

                @case('published')

                <span class="inline-flex w-fit rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-semibold text-emerald-800">
                    Published
                </span>

                @break

                @case('closed')

                <span class="inline-flex w-fit rounded-full bg-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700">
                    Closed
                </span>

                @break

                @endswitch

            </div>

            <div class="mt-6">

                <p class="text-xs font-semibold uppercase text-gray-500">
                    Deskripsi
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-gray-700">
                    {{ $tugas->deskripsi }}
                </p>

            </div>

            <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2">

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Tanggal Mulai
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $tugas->tanggal_mulai->translatedFormat('d F Y, H:i') }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase text-gray-500">
                        Deadline
                    </p>

                    <p class="mt-1 text-sm font-semibold text-gray-900">
                        {{ $tugas->tanggal_deadline->translatedFormat('d F Y, H:i') }}
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

        {{-- Aksi --}}
        @if ($tugas->status === 'draft')

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-wrap gap-3">

                <a
                    href="{{ route('mentor.tugas.edit', $tugas) }}"
                    class="rounded-xl border border-gray-300 bg-white px-5 py-3 text-sm font-semibold text-gray-700">
                    Edit
                </a>

                <form
                    method="POST"
                    action="{{ route('mentor.tugas.publish', $tugas) }}">
                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                        Publish
                    </button>

                </form>

            </div>

        </div>

        @elseif ($tugas->status === 'published')

        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <form
                method="POST"
                action="{{ route('mentor.tugas.close', $tugas) }}">
                @csrf

                <button
                    type="submit"
                    class="rounded-xl bg-gray-700 px-5 py-3 text-sm font-semibold text-white hover:bg-gray-800">
                    Tutup Tugas
                </button>

            </form>

        </div>

        @endif

        {{-- Pengumpulan --}}
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm">

            <div class="border-b border-gray-200 px-6 py-5">

                <h3 class="font-bold text-gray-900">
                    Pengumpulan Tugas
                </h3>

                <p class="mt-1 text-sm text-gray-500">
                    Status pengumpulan mahasiswa untuk tugas ini.
                </p>

            </div>

            <div class="overflow-x-auto">

                <table class="min-w-full divide-y divide-gray-200">

                    <thead class="bg-gray-50">

                        <tr>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Mahasiswa
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Status
                            </th>

                            <th class="px-5 py-4 text-center text-xs font-semibold uppercase text-gray-500">
                                Nilai
                            </th>

                            <th class="px-5 py-4 text-left text-xs font-semibold uppercase text-gray-500">
                                Dikumpulkan
                            </th>

                        </tr>

                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse ($tugas->pengumpulan as $pengumpulan)

                        <tr>

                            <td class="px-5 py-4">

                                <p class="font-semibold text-gray-900">
                                    {{ $pengumpulan->mahasiswa->user->name }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $pengumpulan->mahasiswa->nim }}
                                </p>

                            </td>

                            <td class="px-5 py-4 text-center text-sm">
                                {{ ucfirst($pengumpulan->status) }}
                            </td>

                            <td class="px-5 py-4 text-center text-sm">
                                {{ $pengumpulan->nilai ?? '-' }}
                            </td>

                            <td class="px-5 py-4 text-sm text-gray-600">
                                {{ $pengumpulan->dikumpulkan_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="4"
                                class="px-6 py-12 text-center text-sm text-gray-500">
                                Belum ada pengumpulan.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</x-simaga-layout>