<x-simaga-layout>
    <x-slot:title>Ganti Password - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Ganti Password</x-slot:headerTitle>

    <div class="flex min-h-[70vh] items-center justify-center">
        <div class="w-full max-w-lg">

            {{-- Header --}}
            <div class="mb-6 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-300">
                    <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1.8"
                            d="M12 15v2m0-9v4m9 0a9 9 0 11-18 0 9 9 0 0118 0z"
                        />
                    </svg>
                </div>

                <h3 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                    Ganti Password Anda
                </h3>

                <p class="mt-2 text-sm leading-6 text-gray-500 dark:text-gray-400">
                    Untuk keamanan akun, Anda wajib mengganti password sementara sebelum dapat menggunakan SIMAGA PTA.
                </p>
            </div>

            {{-- Card --}}
            <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

                <form
                    method="POST"
                    action="{{ route('force-password.update') }}"
                    class="space-y-6 p-6 sm:p-8"
                >
                    @csrf
                    @method('PUT')

                    {{-- Error --}}
                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-900/40 dark:bg-red-900/20">
                            <h4 class="text-sm font-semibold text-red-800 dark:text-red-200">
                                Periksa kembali data
                            </h4>

                            <ul class="mt-2 list-disc space-y-1 pl-5 text-sm text-red-700 dark:text-red-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Password Baru --}}
                    <div>
                        <label
                            for="password"
                            class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Password Baru
                        </label>

                        <input
                            id="password"
                            name="password"
                            type="password"
                            required
                            autocomplete="new-password"
                            placeholder="Masukkan password baru"
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-500"
                        >

                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Minimal 8 karakter.
                        </p>
                    </div>

                    {{-- Konfirmasi --}}
                    <div>
                        <label
                            for="password_confirmation"
                            class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Konfirmasi Password Baru
                        </label>

                        <input
                            id="password_confirmation"
                            name="password_confirmation"
                            type="password"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password baru"
                            class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-500"
                        >
                    </div>

                    {{-- Info --}}
                    <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-4 dark:border-emerald-900/40 dark:bg-emerald-900/20">
                        <div class="flex gap-3">
                            <div class="mt-0.5 flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 110 20 10 10 0 010-20z"
                                    />
                                </svg>
                            </div>

                            <div>
                                <h4 class="text-sm font-semibold text-emerald-900 dark:text-emerald-200">
                                    Keamanan Akun
                                </h4>

                                <p class="mt-1 text-sm leading-6 text-emerald-700 dark:text-emerald-300">
                                    Setelah password berhasil diubah, Anda dapat melanjutkan ke dashboard SIMAGA PTA sesuai dengan role akun Anda.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="inline-flex w-full items-center justify-center rounded-xl bg-emerald-700 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
                    >
                        Simpan Password Baru
                    </button>

                </form>
            </div>

        </div>
    </div>
</x-simaga-layout>