<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        Sertifikat Magang
    </title>

    <style>
        @page {
            size: A4 landscape;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            background: #ffffff;
            color: #1f2937;
        }

        .certificate {
            width: 100%;
            height: 100vh;
            padding: 55px;
            position: relative;
            border: 16px solid #064e3b;
        }

        .inner {
            width: 100%;
            height: 100%;
            border: 2px solid #d4af37;
            padding: 35px 55px;
            text-align: center;
            position: relative;
        }

        .institution {
            font-size: 18px;
            font-weight: bold;
            letter-spacing: 1.2px;
            color: #064e3b;
            text-transform: uppercase;
        }

        .title {
            margin-top: 18px;
            font-size: 34px;
            font-weight: bold;
            letter-spacing: 3px;
            color: #064e3b;
        }

        .subtitle {
            margin-top: 8px;
            font-size: 16px;
            color: #6b7280;
        }

        .recipient-label {
            margin-top: 28px;
            font-size: 14px;
            color: #6b7280;
        }

        .name {
            margin-top: 8px;
            font-size: 30px;
            font-weight: bold;
            color: #111827;
        }

        .nim {
            margin-top: 5px;
            font-size: 15px;
            color: #4b5563;
        }

        .description {
            margin: 20px auto 0;
            max-width: 720px;
            font-size: 15px;
            line-height: 1.7;
            color: #374151;
        }

        .number {
            margin-top: 20px;
            font-size: 14px;
            font-weight: bold;
            color: #064e3b;
        }

        .signatures {
            margin-top: 25px;
            width: 100%;
            display: table;
            table-layout: fixed;
        }

        .signature {
            display: table-cell;
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }

        .signature-title {
            font-size: 13px;
            color: #6b7280;
        }

        .signature-name {
            margin-top: 50px;
            font-size: 14px;
            font-weight: bold;
        }

        .signature-role {
            margin-top: 4px;
            font-size: 12px;
            color: #6b7280;
        }
    </style>

</head>

<body>

    <div class="certificate">

        <div class="inner">

            <div class="institution">
                PENGADILAN TINGGI AGAMA PALU
            </div>

            <div class="subtitle">
                Provinsi Sulawesi Tengah
            </div>

            <div class="title">
                SERTIFIKAT MAGANG
            </div>

            <div class="recipient-label">
                Diberikan kepada:
            </div>

            <div class="name">
                {{ $sertifikat->mahasiswa->user->name }}
            </div>

            <div class="nim">
                NIM {{ $sertifikat->mahasiswa->nim }}
            </div>

            <div class="description">

                Telah melaksanakan kegiatan magang pada
                <strong>
                    Pengadilan Tinggi Agama Palu
                </strong>
                dalam rangka pelaksanaan kegiatan praktik kerja/magang
                selama periode
                <strong>
                    {{ $sertifikat->penempatan->periodeMagang->nama_periode }}
                </strong>.

                <br>

                Sertifikat ini diterbitkan sebagai bukti telah
                menyelesaikan kegiatan magang sesuai dengan
                ketentuan yang berlaku.

            </div>

            <div class="number">
                Nomor: {{ $sertifikat->nomor_sertifikat }}
            </div>

            <div class="signatures">

                <div class="signature">

                    <div class="signature-title">
                        Mentor
                    </div>

                    <div class="signature-name">
                        {{ $sertifikat->mentor->user->name }}
                    </div>

                    <div class="signature-role">
                        Pembimbing Magang
                    </div>

                </div>

                <div class="signature">

                    <div class="signature-title">
                        Pengadilan Tinggi Agama Palu
                    </div>

                    <div class="signature-name">
                        Administrator PTA
                    </div>

                    <div class="signature-role">
                        Pejabat yang Berwenang
                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>