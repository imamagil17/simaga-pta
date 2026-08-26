<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Profil Instansi
        |--------------------------------------------------------------------------
        */

        Setting::setValue(
            'nama_instansi',
            'Pengadilan Tinggi Agama Palu',
            'instansi',
            'Nama instansi yang digunakan pada SIMAGA.'
        );

        Setting::setValue(
            'alamat_instansi',
            'Palu, Sulawesi Tengah',
            'instansi',
            'Alamat instansi.'
        );

        /*
        |--------------------------------------------------------------------------
        | Jam Kerja
        |--------------------------------------------------------------------------
        */

        Setting::setValue(
            'jam_masuk',
            '08:00',
            'jam_kerja',
            'Jam masuk resmi mahasiswa magang.'
        );

        Setting::setValue(
            'jam_pulang_senin_kamis',
            '16:30',
            'jam_kerja',
            'Jam pulang Senin sampai Kamis.'
        );

        Setting::setValue(
            'jam_pulang_jumat',
            '16:50',
            'jam_kerja',
            'Jam pulang hari Jumat.'
        );

        /*
        |--------------------------------------------------------------------------
        | Bobot Penilaian
        |--------------------------------------------------------------------------
        */

        Setting::setValue(
            'bobot_kedisiplinan',
            '20',
            'penilaian',
            'Bobot penilaian kedisiplinan dalam persen.'
        );

        Setting::setValue(
            'bobot_kehadiran',
            '20',
            'penilaian',
            'Bobot penilaian kehadiran dalam persen.'
        );

        Setting::setValue(
            'bobot_kinerja',
            '20',
            'penilaian',
            'Bobot penilaian kinerja dalam persen.'
        );

        Setting::setValue(
            'bobot_kompetensi',
            '20',
            'penilaian',
            'Bobot penilaian kompetensi dalam persen.'
        );

        Setting::setValue(
            'bobot_sikap',
            '20',
            'penilaian',
            'Bobot penilaian sikap dalam persen.'
        );

        /*
|--------------------------------------------------------------------------
| Hari Kerja
|--------------------------------------------------------------------------
*/

        Setting::setValue(
            'hari_kerja_senin',
            '1',
            'hari_kerja',
            'Senin merupakan hari kerja.'
        );

        Setting::setValue(
            'hari_kerja_selasa',
            '1',
            'hari_kerja',
            'Selasa merupakan hari kerja.'
        );

        Setting::setValue(
            'hari_kerja_rabu',
            '1',
            'hari_kerja',
            'Rabu merupakan hari kerja.'
        );

        Setting::setValue(
            'hari_kerja_kamis',
            '1',
            'hari_kerja',
            'Kamis merupakan hari kerja.'
        );

        Setting::setValue(
            'hari_kerja_jumat',
            '1',
            'hari_kerja',
            'Jumat merupakan hari kerja.'
        );

        Setting::setValue(
            'hari_kerja_sabtu',
            '0',
            'hari_kerja',
            'Sabtu merupakan hari libur.'
        );

        Setting::setValue(
            'hari_kerja_minggu',
            '0',
            'hari_kerja',
            'Minggu merupakan hari libur.'
        );
    }
}
