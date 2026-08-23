<x-simaga-layout>
    <x-slot:title>Lengkapi Profil Mentor - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Lengkapi Profil Mentor</x-slot:headerTitle>

    <div class="max-w-5xl mx-auto space-y-6">

        <!-- Top Header & Back Button -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white dark:bg-gray-800 p-6 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div>
                <a href="{{ route('admin.mentors.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-emerald-700 dark:text-emerald-400 hover:underline mb-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali ke Daftar Mentor
                </a>
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <svg class="w-6 h-6 text-emerald-700 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Lengkapi Profil Mentor
                </h3>
                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Isi data profil dan kepegawaian untuk akun mentor terdaftar.
                </p>
            </div>
            <div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-amber-50 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300 border border-amber-200 dark:border-amber-700/50">
                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                    Profil Belum Lengkap
                </span>
            </div>
        </div>

        <!-- Form Store Profil -->
        <form method="POST" action="{{ route('admin.mentors.profile.store', $user) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Section 1: Profil Akun (Informasi Akun) -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-4">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        Bagian 1: Profil Akun
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Informasi akun terdaftar dari pengguna (Read-only).</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50 dark:bg-gray-700/40 p-4 rounded-xl border border-gray-200/80 dark:border-gray-700/80">
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Nama Lengkap</span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $user->name }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Alamat Email</span>
                        <span class="block text-sm font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ $user->email }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-medium text-gray-500 dark:text-gray-400">Role Sistem</span>
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 mt-1">
                            Mentor
                        </span>
                    </div>
                </div>
            </div>

            <!-- Section 2: Data Kepegawaian -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-4">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        Bagian 2: Data Kepegawaian
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Informasi nomor induk dan unit kerja mentor.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- NIP -->
                    <div>
                        <label for="nip" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            NIP
                        </label>
                        <input type="text" name="nip" id="nip" value="{{ old('nip') }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-emerald-500 focus:border-emerald-500" 
                               placeholder="Masukkan NIP">
                        @error('nip')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="jabatan" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Jabatan
                        </label>
                        <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-emerald-500 focus:border-emerald-500" 
                               placeholder="Masukkan jabatan mentor">
                        @error('jabatan')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Bagian -->
                    <div>
                        <label for="bagian" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Bagian
                        </label>
                        <input type="text" name="bagian" id="bagian" value="{{ old('bagian') }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-emerald-500 focus:border-emerald-500" 
                               placeholder="Masukkan bagian/unit kerja">
                        @error('bagian')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Informasi Pribadi -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-4">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        Bagian 3: Informasi Pribadi
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Data pribadi dan kontak mentor.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="jenis_kelamin" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Jenis Kelamin
                        </label>
                        <select name="jenis_kelamin" id="jenis_kelamin" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Pilih Jenis Kelamin</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin') === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                        @error('jenis_kelamin')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Agama -->
                    <div>
                        <label for="agama" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Agama
                        </label>
                        <select name="agama" id="agama" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="">Pilih Agama</option>
                            @foreach(['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'] as $agamaOption)
                                <option value="{{ $agamaOption }}" {{ old('agama') === $agamaOption ? 'selected' : '' }}>{{ $agamaOption }}</option>
                            @endforeach
                        </select>
                        @error('agama')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- No. HP -->
                    <div>
                        <label for="no_hp" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            No. HP
                        </label>
                        <input type="tel" name="no_hp" id="no_hp" value="{{ old('no_hp') }}"
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-emerald-500 focus:border-emerald-500" 
                               placeholder="Contoh: 08xxxxxxxxxx">
                        @error('no_hp')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Foto Profil -->
                    <div>
                        <label for="foto" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Foto Profil
                        </label>
                        <input type="file" name="foto" id="foto" accept="image/jpeg,image/png,image/webp" 
                               class="w-full text-xs text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 dark:file:bg-emerald-900/50 dark:file:text-emerald-300 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900">
                        <span class="block text-[11px] text-gray-400 dark:text-gray-500 mt-1">Format JPG, PNG, atau WebP. Maksimal 2 MB.</span>
                        @error('foto')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Section 4: Status & Keterangan -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm space-y-4">
                <div class="border-b border-gray-200 dark:border-gray-700 pb-3">
                    <h4 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
                        Bagian 4: Status & Keterangan
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Status ketersediaan profil dan catatan tambahan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status Profil -->
                    <div>
                        <label for="status" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Status Profil <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" 
                                class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-emerald-500 focus:border-emerald-500">
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                        @error('status')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Keterangan -->
                    <div class="md:col-span-2">
                        <label for="keterangan" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3" 
                                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-emerald-500 focus:border-emerald-500" 
                                  placeholder="Tambahkan keterangan jika diperlukan...">{{ old('keterangan') }}</textarea>
                        @error('keterangan')
                            <span class="text-xs text-rose-600 dark:text-rose-400 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <a href="{{ route('admin.mentors.index') }}" 
                   class="px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 rounded-lg bg-emerald-700 hover:bg-emerald-800 text-xs font-semibold text-white shadow-sm transition">
                    Simpan Profil
                </button>
            </div>

        </form>

    </div>
</x-simaga-layout>
