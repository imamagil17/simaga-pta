<x-simaga-layout>

    @php
    \Carbon\Carbon::setLocale('id');
    $now = \Carbon\Carbon::now();
    @endphp

    <x-slot:title>Tugas - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Tugas</x-slot:headerTitle>

    <div class="mx-auto max-w-6xl space-y-6">

        @if (session('success'))

        <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">

            <p class="text-sm font-semibold text-emerald-800 dark:text-emerald-300">
                {{ session('success') }}
            </p>

        </div>

        @endif

        @if ($errors->any())

        <div class="rounded-2xl border border-rose-200 bg-rose-50 p-4 dark:border-rose-900/40 dark:bg-rose-900/20">

            <ul class="space-y-1 text-sm text-rose-800 dark:text-rose-300">

                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach

            </ul>

        </div>

        @endif

        <div class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800 sm:flex-row sm:items-center sm:justify-between">

            <div>

                <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 dark:text-emerald-400">
                    Aktivitas Magang
                </p>

                <h3 class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                    Tugas
                </h3>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Lihat dan kerjakan tugas yang diberikan mentor.
                </p>

            </div>

            <a
                href="{{ route('mahasiswa.dashboard') }}"
                class="inline-flex w-fit items-center gap-2 rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm font-semibold text-gray-700 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                Dashboard
            </a>

        </div>

        @if (! $penempatan)

        <div class="rounded-2xl border border-amber-200 bg-amber-50 p-6 dark:border-amber-900/40 dark:bg-amber-900/20">

            <p class="font-semibold text-amber-900 dark:text-amber-200">
                Belum Ada Penempatan Magang
            </p>

            <p class="mt-1 text-sm text-amber-800 dark:text-amber-300">
                Anda belum memiliki penempatan magang aktif.
            </p>

        </div>

        @else

        <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mentor
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->mentor->user->name }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $penempatan->periodeMagang->nama_periode }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Jumlah Tugas
                    </p>

                    <p class="mt-1 font-semibold text-gray-900 dark:text-gray-100">
                        {{ $tugas->count() }}
                    </p>

                </div>

            </div>

        </div>

        <div class="grid grid-cols-1 gap-5">

            @forelse ($tugas as $item)

            @php
            $pengumpulan = $item->pengumpulan
            ->where('mahasiswa_id', $penempatan->mahasiswa_id)
            ->first();

            $deadline = \Carbon\Carbon::parse($item->tanggal_deadline);
            $mulai = \Carbon\Carbon::parse($item->tanggal_mulai);

            $belumMulai = $now->lt($mulai);
            $terlambat = $now->gt($deadline);
            @endphp

            <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                    <div>

                        <div class="flex flex-wrap items-center gap-2">

                            <h4 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                {{ $item->judul }}
                            </h4>

                            @if ($item->status === 'closed')

                            <span class="rounded-full bg-gray-200 px-3 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                                Closed
                            </span>

                            @elseif ($pengumpulan?->status === 'submitted')

                            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-800">
                                Sudah Dikumpulkan
                            </span>

                            @elseif ($pengumpulan?->status === 'reviewed')

                            <span class="rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-800">
                                Sudah Dinilai
                            </span>

                            @elseif ($pengumpulan?->status === 'revision')

                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-800">
                                Perlu Revisi
                            </span>

                            @elseif ($belumMulai)

                            <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-700">
                                Belum Dimulai
                            </span>

                            @elseif ($terlambat)

                            <span class="rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-800">
                                Terlambat
                            </span>

                            @else

                            <span class="rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-800">
                                Belum Dikerjakan
                            </span>

                            @endif

                        </div>

                        <p class="mt-2 line-clamp-2 text-sm leading-6 text-gray-600 dark:text-gray-300">
                            {{ $item->deskripsi }}
                        </p>

                    </div>

                    <a
                        href="{{ route('mahasiswa.tugas.show', $item) }}"
                        class="inline-flex w-fit items-center justify-center rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-800">
                        Detail
                    </a>

                </div>

                <div class="mt-5 grid grid-cols-1 gap-4 border-t border-gray-100 pt-5 dark:border-gray-700 sm:grid-cols-2">

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Mulai
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                            {{ $mulai->translatedFormat('d F Y, H:i') }}
                        </p>

                    </div>

                    <div>

                        <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                            Deadline
                        </p>

                        <p class="mt-1 text-sm font-semibold text-gray-800 dark:text-gray-200">
                            {{ $deadline->translatedFormat('d F Y, H:i') }}
                        </p>

                    </div>

                </div>

            </div>

            @empty

            <div class="rounded-2xl border border-dashed border-gray-300 bg-gray-50 px-6 py-16 text-center dark:border-gray-700 dark:bg-gray-900/40">

                <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                    Belum Ada Tugas
                </h4>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Belum ada tugas yang diberikan oleh mentor.
                </p>

            </div>

            @endforelse

        </div>

        @endif

    </div>

</x-simaga-layout>