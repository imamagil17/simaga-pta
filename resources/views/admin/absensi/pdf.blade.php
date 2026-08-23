<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">

    <title>Laporan Absensi Mahasiswa</title>

    <style>
        @page {
            margin: 30px 25px;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 9px;
            color: #1f2937;
        }

        .header {
            text-align: center;
            margin-bottom: 16px;
        }

        .institution {
            font-size: 15px;
            font-weight: bold;
            color: #064e3b;
        }

        .title {
            margin-top: 4px;
            font-size: 13px;
            font-weight: bold;
        }

        .subtitle {
            margin-top: 3px;
            font-size: 9px;
            color: #6b7280;
        }

        .line {
            border-top: 2px solid #064e3b;
            margin-top: 10px;
            margin-bottom: 12px;
        }

        .filter-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }

        .filter-table td {
            padding: 3px 5px;
            vertical-align: top;
        }

        .filter-label {
            width: 75px;
            font-weight: bold;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .summary-table td {
            width: 12.5%;
            padding: 7px;
            border: 1px solid #d1d5db;
            text-align: center;
        }

        .summary-label {
            display: block;
            font-size: 7px;
            color: #6b7280;
        }

        .summary-value {
            display: block;
            margin-top: 2px;
            font-size: 13px;
            font-weight: bold;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #064e3b;
            color: white;
            border: 1px solid #064e3b;
            padding: 6px 5px;
            text-align: center;
            font-size: 8px;
        }

        .data-table td {
            border: 1px solid #d1d5db;
            padding: 5px;
            vertical-align: middle;
        }

        .center {
            text-align: center;
        }

        .footer {
            margin-top: 15px;
            font-size: 8px;
            color: #6b7280;
            text-align: right;
        }
    </style>
</head>

<body>

    {{-- ============================================================
         HEADER
         ============================================================ --}}
    <div class="header">

        <div class="institution">
            PENGADILAN TINGGI AGAMA PALU
        </div>

        <div class="title">
            LAPORAN ABSENSI MAHASISWA MAGANG
        </div>

        <div class="subtitle">
            SIMAGA PTA
        </div>

    </div>

    <div class="line"></div>

    {{-- ============================================================
         FILTER
         ============================================================ --}}
    <table class="filter-table">

        <tr>

            <td class="filter-label">
                Periode
            </td>

            <td>
                @if ($filters['periode_id'])
                {{ $absensis->first()?->penempatan?->periodeMagang?->nama_periode ?? '-' }}
                @else
                Semua Periode
                @endif
            </td>

            <td class="filter-label">
                Mentor
            </td>

            <td>
                @if ($filters['mentor_id'])
                {{ $absensis->first()?->penempatan?->mentor?->user?->name ?? '-' }}
                @else
                Semua Mentor
                @endif
            </td>

        </tr>

        <tr>

            <td class="filter-label">
                Verifikasi
            </td>

            <td>

                @switch($filters['status_verifikasi'])

                @case('approved')
                Disetujui
                @break

                @case('pending')
                Menunggu
                @break

                @case('rejected')
                Ditolak
                @break

                @default
                Semua Status

                @endswitch

            </td>

            <td class="filter-label">
                Tanggal
            </td>

            <td>

                {{ $filters['tanggal']
                    ? \Carbon\Carbon::parse($filters['tanggal'])->translatedFormat('d F Y')
                    : 'Semua Tanggal'
                }}

            </td>

        </tr>

    </table>

    {{-- ============================================================
         REKAP
         ============================================================ --}}
    <table class="summary-table">

        <tr>

            <td>
                <span class="summary-label">
                    TOTAL
                </span>

                <span class="summary-value">
                    {{ $rekap['total'] }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    HADIR
                </span>

                <span class="summary-value">
                    {{ $rekap['hadir'] }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    IZIN
                </span>

                <span class="summary-value">
                    {{ $rekap['izin'] }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    SAKIT
                </span>

                <span class="summary-value">
                    {{ $rekap['sakit'] }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    ALPA
                </span>

                <span class="summary-value">
                    {{ $rekap['alpa'] }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    TERLAMBAT
                </span>

                <span class="summary-value">
                    {{ $rekap['terlambat'] }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    MENIT TERLAMBAT
                </span>

                <span class="summary-value">
                    {{ $rekap['total_menit_terlambat'] }}
                </span>
            </td>

            <td>
                <span class="summary-label">
                    DISETUJUI
                </span>

                <span class="summary-value">
                    {{ $rekap['approved'] }}
                </span>
            </td>

        </tr>

    </table>

    {{-- ============================================================
         DATA ABSENSI
         ============================================================ --}}
    <table class="data-table">

        <thead>

            <tr>

                <th>No</th>
                <th>NIM</th>
                <th>Mahasiswa</th>
                <th>Mentor</th>
                <th>Tanggal</th>
                <th>Masuk</th>
                <th>Pulang</th>
                <th>Status</th>
                <th>Terlambat</th>
                <th>Verifikasi</th>

            </tr>

        </thead>

        <tbody>

            @forelse ($absensis as $absensi)

            <tr>

                <td class="center">
                    {{ $loop->iteration }}
                </td>

                <td>
                    {{ $absensi->penempatan->mahasiswa->nim ?? '-' }}
                </td>

                <td>
                    {{ $absensi->penempatan->mahasiswa->user->name ?? '-' }}
                </td>

                <td>
                    {{ $absensi->penempatan->mentor->user->name ?? '-' }}
                </td>

                <td class="center">
                    {{ $absensi->tanggal->format('d-m-Y') }}
                </td>

                <td class="center">
                    {{ $absensi->jam_masuk
                            ? substr($absensi->jam_masuk, 0, 5)
                            : '-'
                        }}
                </td>

                <td class="center">
                    {{ $absensi->jam_pulang
                            ? substr($absensi->jam_pulang, 0, 5)
                            : '-'
                        }}
                </td>

                <td class="center">
                    {{ ucfirst($absensi->status_kehadiran ?? '-') }}
                </td>

                <td class="center">
                    {{ $absensi->menit_terlambat !== null
                            ? $absensi->menit_terlambat . ' menit'
                            : '-'
                        }}
                </td>

                <td class="center">

                    @switch($absensi->status_verifikasi)

                    @case('approved')
                    Disetujui
                    @break

                    @case('rejected')
                    Ditolak
                    @break

                    @default
                    Menunggu

                    @endswitch

                </td>

            </tr>

            @empty

            <tr>

                <td
                    colspan="10"
                    class="center">
                    Tidak ada data absensi.
                </td>

            </tr>

            @endforelse

        </tbody>

    </table>

    {{-- ============================================================
         FOOTER
         ============================================================ --}}
    <div class="footer">
        Dicetak pada
        {{ now()->translatedFormat('d F Y, H:i') }}
        WIB
    </div>

</body>

</html>