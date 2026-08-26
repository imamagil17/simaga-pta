<x-simaga-layout>

    <x-slot:title>Pengajuan Sertifikat - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Pengajuan Sertifikat</x-slot:headerTitle>

    <div class="mx-auto max-w-4xl space-y-6">

        <div>

            <a
                href="{{ route('mentor.sertifikat.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-gray-600 hover:text-emerald-700">
                ← Kembali ke Sertifikat
            </a>

        </div>

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

        {{-- Identitas --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mahasiswa
                    </p>

                    <p class="mt-1 text-lg font-bold text-gray-900">
                        {{ $penempatan->mahasiswa->user->name }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $penempatan->mahasiswa->nim }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mentor
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $penempatan->mentor->user->name }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $penempatan->periodeMagang->nama_periode }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Status --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Status Pengajuan
            </p>

            @if (! $sertifikat)

            <div class="mt-4">

                <span class="inline-flex rounded-full bg-gray-100 px-4 py-2 text-sm font-semibold text-gray-700">
                    Belum Diajukan
                </span>

                <p class="mt-4 text-sm leading-6 text-gray-600">
                    Anda dapat mengajukan pembuatan sertifikat untuk mahasiswa ini kepada admin.
                </p>

                <form
                    method="POST"
                    action="{{ route('mentor.sertifikat.store', $penempatan->mahasiswa) }}"
                    class="mt-5"
                    onsubmit="return confirm('Ajukan pembuatan sertifikat untuk mahasiswa ini?')">

                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                        Ajukan Sertifikat
                    </button>

                </form>

            </div>

            @elseif ($sertifikat->status === 'pending')

            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-5">

                <span class="inline-flex rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-800">
                    Menunggu Persetujuan Admin
                </span>

                <p class="mt-3 text-sm leading-6 text-amber-800">
                    Pengajuan sertifikat sudah dikirim dan sedang menunggu pemeriksaan admin.
                </p>

            </div>

            @elseif ($sertifikat->status === 'approved')

            <div class="mt-4 rounded-xl border border-emerald-200 bg-emerald-50 p-5">

                <span class="inline-flex rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-800">
                    Disetujui
                </span>

                <p class="mt-3 text-sm text-emerald-800">
                    Pengajuan sertifikat telah disetujui oleh admin.
                </p>

                @if ($sertifikat->nomor_sertifikat)

                <div class="mt-4">

                    <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600">
                        Nomor Sertifikat
                    </p>

                    <p class="mt-1 font-bold text-emerald-900">
                        {{ $sertifikat->nomor_sertifikat }}
                    </p>

                </div>

                @endif

            </div>

            @else

            <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 p-5">

                <span class="inline-flex rounded-full bg-rose-100 px-4 py-2 text-sm font-semibold text-rose-800">
                    Pengajuan Ditolak
                </span>

                @if ($sertifikat->catatan)

                <p class="mt-3 text-sm leading-6 text-rose-800">
                    {{ $sertifikat->catatan }}
                </p>

                @endif

                <form
                    method="POST"
                    action="{{ route('mentor.sertifikat.store', $penempatan->mahasiswa) }}"
                    class="mt-5"
                    onsubmit="return confirm('Ajukan kembali pembuatan sertifikat untuk mahasiswa ini?')">

                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                        Ajukan Kembali
                    </button>

                </form>

            </div>

            @endif

        </div>

    </div>

</x-simaga-layout>