<x-simaga-layout>
    <x-slot:title>Dashboard Administrator PTA - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Dashboard Administrator PTA</x-slot:headerTitle>

    <div class="space-y-6">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-950 rounded-2xl p-6 text-white shadow-lg border border-emerald-700/50">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        Administrator PTA
                    </span>
                    <h3 class="text-xl sm:text-2xl font-bold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-emerald-200 text-sm mt-1 max-w-xl">
                        Selamat bekerja di Sistem Informasi Manajemen Magang (SIMAGA) Pengadilan Tinggi Agama Palu.
                    </p>
                </div>
                <div class="bg-emerald-900/60 backdrop-blur border border-emerald-700/60 px-4 py-2.5 rounded-xl text-right">
                    <div class="text-xs text-emerald-300 font-medium">Peran Akun</div>
                    <div class="text-sm font-bold text-amber-400">Administrator PTA</div>
                </div>
            </div>
        </div>

        <!-- System Overview Cards (Empty States - No fake stats) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Mahasiswa Magang</h4>
                    <span class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 100 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Belum ada data mahasiswa terdaftar.</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Mentor Pembimbing</h4>
                    <span class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Belum ada data mentor terdaftar.</div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Periode Magang</h4>
                    <span class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <div class="text-xs text-gray-500 dark:text-gray-400">Belum ada periode magang aktif.</div>
                </div>
            </div>
        </div>

        <!-- Information / Placeholder Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-2 rounded-lg bg-emerald-100 text-emerald-800 dark:bg-emerald-900/60 dark:text-emerald-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">Status Sistem & Data Modul</h4>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Sistem otentikasi dan hak akses Administrator PTA telah aktif. Modul operasional seperti manajemen pengguna, mentor, mahasiswa, dan periode magang akan dikembangkan secara bertahap.
            </p>
        </div>

    </div>
</x-simaga-layout>
