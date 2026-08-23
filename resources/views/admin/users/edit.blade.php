<x-simaga-layout>
    <x-slot:title>Edit Pengguna - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Edit Pengguna</x-slot:headerTitle>

    <div class="space-y-6">

        {{-- Header --}}
        <div>
            <div class="flex items-center gap-3">
                <a
                    href="{{ route('admin.users.index') }}"
                    class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-gray-200 bg-white text-gray-600 transition hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700"
                    title="Kembali"
                >
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 19l-7-7 7 7"
                        />
                    </svg>
                </a>

                <div>
                    <h3 class="text-xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
                        Edit Pengguna
                    </h3>

                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Perbarui informasi akun pengguna SIMAGA PTA.
                    </p>
                </div>
            </div>
        </div>

        {{-- Form Card --}}
        <div class="max-w-3xl overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">

            <div class="border-b border-gray-200 px-6 py-5 dark:border-gray-700">
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">
                    Informasi Akun
                </h4>

                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Sedang mengedit akun:
                    <span class="font-medium text-gray-700 dark:text-gray-300">
                        {{ $user->name }}
                    </span>
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('admin.users.update', $user) }}"
                class="space-y-6 px-6 py-6"
            >
                @csrf
                @method('PUT')

                {{-- Error Validasi --}}
                @if ($errors->any())
                    <div class="rounded-xl border border-red-200 bg-red-50 p-4 dark:border-red-900/40 dark:bg-red-900/20">
                        <div class="flex gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-red-100 text-red-600 dark:bg-red-900/40 dark:text-red-300">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4m0 4h.01M5.07 19h13.86a2 2 0 001.73-3L13.73 4a2 2 0 00-3.46 0L3.34 16a2 2 0 001.73 3z"
                                    />
                                </svg>
                            </div>

                            <div>
                                <h4 class="text-sm font-semibold text-red-800 dark:text-red-200">
                                    Periksa kembali data
                                </h4>

                                <ul class="mt-1 list-disc space-y-1 pl-5 text-sm text-red-700 dark:text-red-300">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Nama --}}
                <div>
                    <label
                        for="name"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Nama Lengkap
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        required
                        autocomplete="name"
                        class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-500"
                    >
                </div>

                {{-- Email --}}
                <div>
                    <label
                        for="email"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Email
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        required
                        autocomplete="email"
                        class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition placeholder:text-gray-400 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100 dark:placeholder:text-gray-500"
                    >
                </div>

                {{-- Role --}}
                <div>
                    <label
                        for="role"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Role Pengguna
                    </label>

                    <select
                        id="role"
                        name="role"
                        required
                        class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                    >
                        <option value="mentor" @selected(old('role', $user->role) === 'mentor')}>
                            Mentor
                        </option>

                        <option value="mahasiswa" @selected(old('role', $user->role) === 'mahasiswa')}>
                            Mahasiswa Magang
                        </option>

                        @if ($user->role === 'administrator')
                            <option value="administrator" selected>
                                Administrator PTA
                            </option>
                        @endif
                    </select>

                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Administrator PTA tidak dapat diubah menjadi role lain secara tidak sengaja.
                    </p>
                </div>

                {{-- Status --}}
                <div>
                    <label
                        for="status"
                        class="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300"
                    >
                        Status Akun
                    </label>

                    <select
                        id="status"
                        name="status"
                        required
                        class="block w-full rounded-xl border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 shadow-sm outline-none transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-100"
                    >
                        <option value="active" @selected(old('status', $user->status) === 'active')}>
                            Aktif
                        </option>

                        <option value="inactive" @selected(old('status', $user->status) === 'inactive')}>
                            Tidak Aktif
                        </option>
                    </select>
                </div>

                {{-- Password Status --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 dark:border-amber-900/40 dark:bg-amber-900/20">
                    <div class="flex gap-3">

                        <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 11c0-1.105.895-2 2-2s2 .895 2 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2m2 5v2m7-7V9a7 7 0 10-14 0v2"
                                />
                            </svg>
                        </div>

                        <div>
                            <h4 class="text-sm font-semibold text-amber-900 dark:text-amber-200">
                                Status Password
                            </h4>

                            <p class="mt-1 text-sm leading-6 text-amber-700 dark:text-amber-300">
                                @if ($user->must_change_password)
                                    Pengguna masih diwajibkan mengganti password pada login berikutnya.
                                @else
                                    Pengguna sudah menggunakan password pribadi.
                                @endif
                            </p>
                        </div>

                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-6 sm:flex-row sm:justify-end dark:border-gray-700">

                    <a
                        href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center justify-center rounded-xl border border-gray-300 px-5 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                    >
                        Batal
                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-emerald-700 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-emerald-800"
                    >
                        Simpan Perubahan
                    </button>

                </div>
            </form>
        </div>

    </div>
</x-simaga-layout>