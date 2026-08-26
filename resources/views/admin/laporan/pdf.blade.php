<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Magang
    </title>

    <style>
        @page {
            margin: 35px 40px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #1f2937;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #065f46;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }

        .institution {
            font-size: 16px;
            font-weight: bold;
            color: #064e3b;
            text-transform: uppercase;
        }

        .subtitle {
            margin-top: 3px;
            font-size: 10px;
            color: #6b7280;
        }

        .title {
            margin-top: 14px;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .section {
            margin-top: 18px;
        }

        .section-title {
            background: #ecfdf5;
            border-left: 4px solid #059669;
            padding: 7px 10px;
            font-size: 12px;
            font-weight: bold;
            color: #065f46;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table {
            margin-top: 8px;
        }

        .info-table td {
            padding: 4px 6px;
            vertical-align: top;
        }

        .info-label {
            width: 28%;
            font-weight: bold;
        }

        .summary-table {
            margin-top: 10px;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #d1d5db;
            padding: 7px;
            text-align: center;
        }

        .summary-table th {
            background: #f3f4f6;
            font-weight: bold;
        }

        .nilai-akhir {
            margin-top: 12px;
            padding: 12px;
            text-align: center;
            border: 1px solid #a7f3d0;
            background: #ecfdf5;
        }

        .nilai-label {
            font-size: 10px;
            color: #047857;
        }

        .nilai {
            margin-top: 3px;
            font-size: 25px;
            font-weight: bold;
            color: #065f46;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
            color: #6b7280;
        }
    </style>

</head>

<body>

    <div class="header">

        <div class="institution">
            PENGADILAN TINGGI AGAMA PALU
        </div>

        <div class="subtitle">
            Sistem Informasi Manajemen Magang
        </div>

        <div class="title">
            Laporan Magang Mahasiswa
        </div>

    </div>

    {{-- Identitas --}}
    <div class="section">

        <div class="section-title">
            Identitas Mahasiswa
        </div>

        <table class="info-table">

            <tr>
                <td class="info-label">
                    Nama
                </td>

                <td>
                    {{ $laporan['mahasiswa']['nama'] }}
                </td>
            </tr>

            <tr>
                <td class="info-label">
                    NIM
                </td>

                <td>
                    {{ $laporan['mahasiswa']['nim'] }}
                </td>
            </tr>

            <tr>
                <td class="info-label">
                    Perguruan Tinggi
                </td>

                <td>
                    {{ $laporan['mahasiswa']['perguruan_tinggi'] }}
                </td>
            </tr>

            <tr>
                <td class="info-label">
                    Program Studi
                </td>

                <td>
                    {{ $laporan['mahasiswa']['program_studi'] }}
                </td>
            </tr>

            <tr>
                <td class="info-label">
                    Mentor
                </td>

                <td>
                    {{ $laporan['mentor'] }}
                </td>
            </tr>

            <tr>
                <td class="info-label">
                    Periode Magang
                </td>

                <td>
                    {{ $laporan['periode'] }}
                </td>
            </tr>

        </table>

    </div>

    {{-- Absensi --}}
    <div class="section">

        <div class="section-title">
            Rekap Absensi
        </div>

        <table class="summary-table">

            <thead>

                <tr>

                    <th>Total</th>
                    <th>Hadir</th>
                    <th>Terlambat</th>
                    <th>Total Menit Terlambat</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>
                        {{ $laporan['absensi']['total'] }}
                    </td>

                    <td>
                        {{ $laporan['absensi']['hadir'] }}
                    </td>

                    <td>
                        {{ $laporan['absensi']['terlambat'] }}
                    </td>

                    <td>
                        {{ $laporan['absensi']['total_menit_terlambat'] }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    {{-- Logbook --}}
    <div class="section">

        <div class="section-title">
            Rekap Logbook
        </div>

        <table class="summary-table">

            <thead>

                <tr>

                    <th>Total</th>
                    <th>Disetujui</th>
                    <th>Menunggu</th>
                    <th>Revisi</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>
                        {{ $laporan['logbook']['total'] }}
                    </td>

                    <td>
                        {{ $laporan['logbook']['approved'] }}
                    </td>

                    <td>
                        {{ $laporan['logbook']['submitted'] }}
                    </td>

                    <td>
                        {{ $laporan['logbook']['revision'] }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    {{-- Tugas --}}
    <div class="section">

        <div class="section-title">
            Rekap Tugas
        </div>

        <table class="summary-table">

            <thead>

                <tr>

                    <th>Total Tugas</th>
                    <th>Disetujui</th>
                    <th>Terkumpul</th>
                    <th>Revisi</th>

                </tr>

            </thead>

            <tbody>

                <tr>

                    <td>
                        {{ $laporan['tugas']['total'] }}
                    </td>

                    <td>
                        {{ $laporan['tugas']['approved'] }}
                    </td>

                    <td>
                        {{ $laporan['tugas']['submitted'] }}
                    </td>

                    <td>
                        {{ $laporan['tugas']['revision'] }}
                    </td>

                </tr>

            </tbody>

        </table>

    </div>

    {{-- Penilaian --}}
    <div class="section">

        <div class="section-title">
            Penilaian
        </div>

        @if ($laporan['penilaian']['nilai_akhir'] !== null)

        <div class="nilai-akhir">

            <div class="nilai-label">
                NILAI AKHIR
            </div>

            <div class="nilai">
                {{ number_format($laporan['penilaian']['nilai_akhir'], 2) }}
            </div>

            <div>

                Status:

                {{
                        $laporan['penilaian']['status'] === 'final'
                            ? 'Final'
                            : 'Draft'
                    }}

            </div>

        </div>

        @else

        <div style="margin-top: 10px;">
            Belum ada penilaian.
        </div>

        @endif

    </div>

    <div class="footer">

        Dicetak melalui SIMAGA PTA

        <br>

        {{ now()->format('d-m-Y H:i') }}

    </div>

</body>

</html>