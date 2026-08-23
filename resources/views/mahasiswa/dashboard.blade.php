<x-simaga-layout>
    <x-slot:title>Dashboard Mahasiswa Magang - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Dashboard Mahasiswa Magang</x-slot:headerTitle>

    <div class="space-y-6">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-950 rounded-2xl p-6 text-white shadow-lg border border-emerald-700/50">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        Mahasiswa Magang
                    </span>
                    <h3 class="text-xl sm:text-2xl font-bold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-emerald-200 text-sm mt-1 max-w-xl">
                        Selamat melaksanakan program magang di Pengadilan Tinggi Agama Palu.
                    </p>
                </div>
                <div class="bg-emerald-900/60 backdrop-blur border border-emerald-700/60 px-4 py-2.5 rounded-xl text-right">
                    <div class="text-xs text-emerald-300 font-medium">Peran Akun</div>
                    <div class="text-sm font-bold text-amber-400">Mahasiswa Magang</div>
                </div>
            </div>
        </div>

        <!-- Student Activity Cards (Empty States) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Absensi Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Absensi Hari Ini</h4>
                    <span class="p-2 rounded-lg bg-emerald-50 dark:bg-emerald-900/40 text-emerald-700 dark:text-emerald-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Fitur absensi belum tersedia.</p>
                </div>
            </div>

            <!-- Logbook Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Logbook Harian</h4>
                    <span class="p-2 rounded-lg bg-amber-50 dark:bg-amber-900/40 text-amber-700 dark:text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Fitur logbook belum tersedia.</p>
                </div>
            </div>

            <!-- Tugas Status -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                <div class="flex items-center justify-between">
                    <h4 class="text-sm font-bold text-gray-800 dark:text-gray-200">Tugas Magang</h4>
                    <span class="p-2 rounded-lg bg-blue-50 dark:bg-blue-900/40 text-blue-700 dark:text-blue-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </span>
                </div>
                <div class="mt-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Fitur tugas belum tersedia.</p>
                </div>
            </div>
        </div>

    </div>
</x-simaga-layout>
