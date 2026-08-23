<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50 dark:bg-gray-900">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'SIMAGA PTA - Pengadilan Tinggi Agama Palu' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- Scripts and Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body
    class="h-full antialiased text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-900"
    x-data="{ sidebarOpen: false }"
>
    <div class="min-h-screen flex flex-col md:flex-row">

        <!-- Mobile Sidebar Backdrop -->
        <div
            x-show="sidebarOpen"
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-gray-900/60 backdrop-blur-sm md:hidden"
        ></div>

        <!-- Sidebar Navigation -->
        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-emerald-950 text-white flex flex-col transition-transform duration-300 ease-in-out md:static md:translate-x-0 border-r border-emerald-800/50 shadow-xl"
        >

            <!-- Sidebar Header -->
            <div class="px-6 py-5 bg-emerald-900/80 border-b border-emerald-800/80 flex items-center justify-between">

                <div class="flex items-center gap-3">

                    <div class="w-10 h-10 rounded-lg bg-amber-500 flex items-center justify-center font-bold text-emerald-950 shadow-md ring-2 ring-amber-400/40">
                        PTA
                    </div>

                    <div>
                        <h1 class="font-bold text-base tracking-wide text-white">
                            SIMAGA PTA
                        </h1>

                        <p class="text-xs text-emerald-300 font-medium">
                            PTA Palu
                        </p>
                    </div>

                </div>

                <button
                    @click="sidebarOpen = false"
                    class="md:hidden text-emerald-300 hover:text-white"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>

            </div>

            <!-- User Role Banner -->
            <div class="px-6 py-3 bg-emerald-900/40 border-b border-emerald-800/40">

                <div class="text-xs text-emerald-300 font-medium">
                    Masuk Sebagai:
                </div>

                <div class="text-sm font-semibold text-amber-400 flex items-center gap-2 mt-0.5">

                    <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span>

                    @if(Auth::user()->isAdministrator())
                        Administrator PTA
                    @elseif(Auth::user()->isMentor())
                        Mentor
                    @elseif(Auth::user()->isMahasiswa())
                        Mahasiswa Magang
                    @else
                        {{ Auth::user()->role }}
                    @endif

                </div>

            </div>

            <!-- Navigation Links -->
            <nav class="flex-1 overflow-y-auto px-4 py-4 space-y-1.5 scrollbar-thin scrollbar-thumb-emerald-800">

                @if(Auth::user()->isAdministrator())

                    <!-- Administrator Menu -->

                    <a
                        href="{{ route('admin.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 011 1v-4a1 1 0 011 1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>

                        Dashboard
                    </a>

                    <!-- Data Magang -->
                    <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-amber-400/90 uppercase tracking-wider">
                        Data Magang
                    </div>

                    {{-- Mahasiswa --}}
                    <a
                        href="{{ route('admin.mahasiswa.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.mahasiswa.*') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.mahasiswa.*') ? 'bg-emerald-950' : 'bg-amber-400' }}"></span>

                        Mahasiswa
                    </a>

                    {{-- Mentor --}}
                    <a
                        href="{{ route('admin.mentors.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.mentors.*') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.mentors.*') ? 'bg-emerald-950' : 'bg-amber-400' }}"></span>

                        Mentor
                    </a>

                    {{-- Periode Magang --}}
                    <a
                        href="{{ route('admin.periode-magangs.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.periode-magangs.*') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.periode-magangs.*') ? 'bg-emerald-950' : 'bg-amber-400' }}"></span>

                        Periode Magang
                    </a>

                    {{-- Penempatan --}}
                    <a
                        href="{{ route('admin.penempatans.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.penempatans.*') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.penempatans.*') ? 'bg-emerald-950' : 'bg-amber-400' }}"></span>

                        Penempatan
                    </a>

                    <!-- Monitoring -->
                    <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-amber-400/90 uppercase tracking-wider">
                        Monitoring
                    </div>

                    @foreach(['Absensi', 'Logbook', 'Tugas'] as $item)

                        <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                            <span class="flex items-center gap-3">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>

                                {{ $item }}
                            </span>

                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                                Segera
                            </span>

                        </div>

                    @endforeach

                    <!-- Lainnya -->
                    <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-amber-400/90 uppercase tracking-wider">
                        Lainnya
                    </div>

                    {{-- Penilaian --}}
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>
                            Penilaian
                        </span>

                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                            Segera
                        </span>

                    </div>

                    {{-- Dokumen --}}
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>
                            Dokumen
                        </span>

                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                            Segera
                        </span>

                    </div>

                    {{-- Laporan --}}
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>
                            Laporan
                        </span>

                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                            Segera
                        </span>

                    </div>

                    {{-- Sertifikat --}}
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>
                            Sertifikat
                        </span>

                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                            Segera
                        </span>

                    </div>

                    {{-- Manajemen Pengguna - Aktif --}}
                    <a
                        href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('admin.users.*') ? 'bg-emerald-950' : 'bg-amber-400' }}"></span>

                        Manajemen Pengguna
                    </a>

                    {{-- Pengaturan - Segera --}}
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>
                            Pengaturan
                        </span>

                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                            Segera
                        </span>

                    </div>

                @elseif(Auth::user()->isMentor())

                    <!-- Mentor Menu -->

                    <a
                        href="{{ route('mentor.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('mentor.dashboard') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                            />
                        </svg>

                        Dashboard
                    </a>

                    <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-amber-400/90 uppercase tracking-wider">
                        Bimbingan & Evaluasi
                    </div>

                    @foreach(['Mahasiswa Bimbingan', 'Absensi', 'Logbook', 'Tugas', 'Penilaian'] as $item)

                        <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                            <span class="flex items-center gap-3">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>

                                {{ $item }}
                            </span>

                            <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                                Segera
                            </span>

                        </div>

                    @endforeach

                @elseif(Auth::user()->isMahasiswa())

                    <!-- Mahasiswa Menu -->

                    <a
                        href="{{ route('mahasiswa.dashboard') }}"
                        class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1-1h-3m-6 0h6"
                            />
                        </svg>

                        Dashboard
                    </a>

                    <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-amber-400/90 uppercase tracking-wider">
                        Aktivitas Magang
                    </div>

                    {{-- Absensi - Aktif --}}
                    <a
                        href="{{ route('mahasiswa.absensi.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('mahasiswa.absensi.*') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                    >
                        <span class="w-1.5 h-1.5 rounded-full {{ request()->routeIs('mahasiswa.absensi.*') ? 'bg-emerald-950' : 'bg-amber-400' }}"></span>

                        Absensi
                    </a>

                    {{-- Logbook - Segera --}}
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>
                            Logbook
                        </span>

                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                            Segera
                        </span>

                    </div>

                    {{-- Tugas - Segera --}}
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>
                            Tugas
                        </span>

                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                            Segera
                        </span>

                    </div>

                    {{-- Dokumen - Segera --}}
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>
                            Dokumen
                        </span>

                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                            Segera
                        </span>

                    </div>

                    {{-- Progress - Segera --}}
                    <div class="flex items-center justify-between px-3 py-2 rounded-lg text-sm text-emerald-300/60 bg-emerald-900/20 cursor-not-allowed select-none">

                        <span class="flex items-center gap-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-700"></span>
                            Progress
                        </span>

                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-emerald-900/60 text-emerald-400 font-medium">
                            Segera
                        </span>

                    </div>

                @endif

                <!-- Pengguna -->
                <div class="pt-3 pb-1 px-3 text-[11px] font-bold text-amber-400/90 uppercase tracking-wider">
                    Pengguna
                </div>

                <a
                    href="{{ route('profile.edit') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ request()->routeIs('profile.edit') ? 'bg-amber-500 text-emerald-950 font-semibold shadow-sm' : 'text-emerald-100 hover:bg-emerald-800/60' }}"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        />
                    </svg>

                    Profil Saya
                </a>

            </nav>

            <!-- Sidebar Footer -->
            <div class="p-4 border-t border-emerald-800/80 bg-emerald-900/40">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold bg-emerald-900 hover:bg-rose-900/80 text-emerald-100 hover:text-white border border-emerald-700 hover:border-rose-700 transition-all duration-200 shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            />
                        </svg>

                        Keluar
                    </button>
                </form>

            </div>

        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0">

            <!-- Top Navbar -->
            <header class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700/60 shadow-sm sticky top-0 z-30">

                <div class="px-4 sm:px-6 lg:px-8 py-3.5 flex items-center justify-between">

                    <div class="flex items-center gap-4">

                        <button
                            @click="sidebarOpen = true"
                            class="md:hidden p-2 rounded-lg text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                            </svg>
                        </button>

                        <div>

                            <h2 class="text-base sm:text-lg font-bold text-emerald-950 dark:text-emerald-400 tracking-tight">
                                {{ $headerTitle ?? 'Dashboard' }}
                            </h2>

                            <p class="text-xs text-gray-500 dark:text-gray-400 hidden sm:block">
                                Pengadilan Tinggi Agama Palu
                            </p>

                        </div>

                    </div>

                    <!-- Right User Info -->
                    <div class="flex items-center gap-3">

                        <div class="text-right hidden sm:block">

                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                {{ Auth::user()->name }}
                            </div>

                            <div class="text-xs text-emerald-700 dark:text-emerald-400 font-medium">

                                @if(Auth::user()->isAdministrator())
                                    Administrator PTA
                                @elseif(Auth::user()->isMentor())
                                    Mentor
                                @elseif(Auth::user()->isMahasiswa())
                                    Mahasiswa Magang
                                @else
                                    {{ Auth::user()->role }}
                                @endif

                            </div>

                        </div>

                        <div class="w-10 h-10 rounded-full bg-emerald-800 text-amber-300 flex items-center justify-center font-bold text-sm shadow-md ring-2 ring-emerald-600/30">
                            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                        </div>

                    </div>

                </div>

            </header>

            <!-- Main Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="py-4 px-6 text-center text-xs text-gray-500 dark:text-gray-400 border-t border-gray-200 dark:border-gray-800">
                &copy; {{ date('Y') }} SIMAGA PTA — Pengadilan Tinggi Agama Palu. Seluruh hak cipta dilindungi.
            </footer>

        </div>

    </div>
</body>
</html>