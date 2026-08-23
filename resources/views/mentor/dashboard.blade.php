<x-simaga-layout>
    <x-slot:title>Dashboard Mentor - SIMAGA PTA</x-slot:title>
    <x-slot:headerTitle>Dashboard Mentor</x-slot:headerTitle>

    <div class="space-y-6">

        <!-- Welcome Banner -->
        <div class="bg-gradient-to-r from-emerald-900 via-emerald-800 to-emerald-950 rounded-2xl p-6 text-white shadow-lg border border-emerald-700/50">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 mb-2">
                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                        Mentor Pembimbing
                    </span>
                    <h3 class="text-xl sm:text-2xl font-bold tracking-tight">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-emerald-200 text-sm mt-1 max-w-xl">
                        Selamat bekerja di halaman khusus Mentor Pembimbing Magang Pengadilan Tinggi Agama Palu.
                    </p>
                </div>
                <div class="bg-emerald-900/60 backdrop-blur border border-emerald-700/60 px-4 py-2.5 rounded-xl text-right">
                    <div class="text-xs text-emerald-300 font-medium">Peran Akun</div>
                    <div class="text-sm font-bold text-amber-400">Mentor</div>
                </div>
            </div>
        </div>

        <!-- Mentor Bimbingan Placeholder -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <div class="flex items-center justify-between border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 rounded-lg bg-amber-100 text-amber-800 dark:bg-amber-900/60 dark:text-amber-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 100 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-gray-100">Daftar Mahasiswa Bimbingan</h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Mahasiswa yang dialokasikan kepada Anda</p>
                    </div>
                </div>
            </div>

            <!-- Empty state -->
            <div class="text-center py-10">
                <div class="w-12 h-12 rounded-full bg-gray-100 dark:bg-gray-700 text-gray-400 flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <h5 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Belum ada mahasiswa bimbingan</h5>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Data mahasiswa bimbingan akan tampil setelah modul penempatan mahasiswa aktif.</p>
            </div>
        </div>

    </div>
</x-simaga-layout>
