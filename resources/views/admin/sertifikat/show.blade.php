<x-simaga-layout>

    <x-slot:title>Detail Sertifikat - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Detail Sertifikat</x-slot:headerTitle>

    <div class="mx-auto max-w-5xl space-y-6">

        <div>

            <a
                href="{{ route('admin.sertifikat.index') }}"
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
                        {{ $sertifikat->mahasiswa->user->name ?? '-' }}
                    </p>

                    <p class="mt-1 text-sm text-gray-500">
                        {{ $sertifikat->mahasiswa->nim ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Mentor Pengaju
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $sertifikat->mentor->user->name ?? '-' }}
                    </p>

                </div>

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Periode
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $sertifikat->penempatan->periodeMagang->nama_periode ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

        {{-- Status --}}
        <div class="rounded-2xl border border-gray-200 bg-white p-6 shadow-sm">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">

                <div>

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Status Pengajuan
                    </p>

                    @if ($sertifikat->status === 'pending')

                    <span class="mt-2 inline-flex rounded-full bg-amber-100 px-4 py-2 text-sm font-semibold text-amber-800">
                        Menunggu Persetujuan
                    </span>

                    @elseif ($sertifikat->status === 'approved')

                    <span class="mt-2 inline-flex rounded-full bg-emerald-100 px-4 py-2 text-sm font-semibold text-emerald-800">
                        Disetujui
                    </span>

                    @else

                    <span class="mt-2 inline-flex rounded-full bg-rose-100 px-4 py-2 text-sm font-semibold text-rose-800">
                        Ditolak
                    </span>

                    @endif

                </div>

                @if ($sertifikat->nomor_sertifikat)

                <div class="text-left sm:text-right">

                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                        Nomor Sertifikat
                    </p>

                    <p class="mt-1 font-bold text-gray-900">
                        {{ $sertifikat->nomor_sertifikat }}
                    </p>

                </div>

                @endif

            </div>

            @if ($sertifikat->catatan)

            <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 p-5">

                <p class="text-xs font-semibold uppercase tracking-wide text-rose-600">
                    Catatan
                </p>

                <p class="mt-2 whitespace-pre-line text-sm leading-7 text-rose-800">
                    {{ $sertifikat->catatan }}
                </p>

            </div>

            @endif

        </div>

        {{-- Aksi Admin --}}
        @if ($sertifikat->status === 'pending')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">

            {{-- Approve --}}
            <div class="rounded-2xl border border-emerald-200 bg-emerald-50 p-6">

                <h3 class="font-bold text-emerald-900">
                    Setujui Pengajuan
                </h3>

                <p class="mt-1 text-sm text-emerald-800">
                    Pengajuan akan disetujui dan nomor sertifikat otomatis dibuat.
                </p>

                <form
                    method="POST"
                    action="{{ route('admin.sertifikat.approve', $sertifikat) }}"
                    class="mt-5"
                    onsubmit="return confirm('Setujui pengajuan sertifikat ini?')">

                    @csrf

                    <button
                        type="submit"
                        class="rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white hover:bg-emerald-800">
                        Setujui Sertifikat
                    </button>

                </form>

            </div>

            {{-- Reject --}}
            <div class="rounded-2xl border border-rose-200 bg-rose-50 p-6">

                <h3 class="font-bold text-rose-900">
                    Tolak Pengajuan
                </h3>

                <p class="mt-1 text-sm text-rose-800">
                    Berikan alasan penolakan kepada mentor.
                </p>

                <form
                    method="POST"
                    action="{{ route('admin.sertifikat.reject', $sertifikat) }}"
                    class="mt-5 space-y-4">

                    @csrf

                    <textarea
                        name="catatan"
                        rows="5"
                        required
                        minlength="5"
                        placeholder="Contoh: Data mahasiswa belum lengkap..."
                        class="block w-full rounded-xl border-rose-300 bg-white text-sm">{{ old('catatan') }}</textarea>

                    <button
                        type="submit"
                        class="rounded-xl bg-rose-600 px-5 py-3 text-sm font-semibold text-white hover:bg-rose-700">
                        Tolak Pengajuan
                    </button>

                </form>

            </div>

        </div>

        @endif

        {{-- Informasi Admin --}}
        @if ($sertifikat->approver)

        <div class="rounded-2xl border border-gray-200 bg-gray-50 p-5">

            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">
                Diproses Admin
            </p>

            <p class="mt-2 text-sm text-gray-700">

                Diproses oleh
                <strong>
                    {{ $sertifikat->approver->name }}
                </strong>

                @if ($sertifikat->approved_at)

                pada
                {{ $sertifikat->approved_at->format('d-m-Y H:i') }}

                @endif

            </p>

        </div>

        @endif

    </div>

</x-simaga-layout>