<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateSettingRequest;
use App\Models\HariLibur;
use App\Models\Setting;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    /**
     * Halaman pengaturan.
     */
    public function index(): View
    {
        $settings = [
            'nama_instansi' =>
            Setting::getValue(
                'nama_instansi',
                'Pengadilan Tinggi Agama Palu'
            ),

            'alamat_instansi' =>
            Setting::getValue(
                'alamat_instansi',
                'Palu, Sulawesi Tengah'
            ),

            'jam_masuk' =>
            Setting::getValue(
                'jam_masuk',
                '08:00'
            ),

            'jam_pulang_senin_kamis' =>
            Setting::getValue(
                'jam_pulang_senin_kamis',
                '16:30'
            ),

            'jam_pulang_jumat' =>
            Setting::getValue(
                'jam_pulang_jumat',
                '16:50'
            ),

            'hari_kerja_senin' =>
            (bool) Setting::getValue(
                'hari_kerja_senin',
                '1'
            ),

            'hari_kerja_selasa' =>
            (bool) Setting::getValue(
                'hari_kerja_selasa',
                '1'
            ),

            'hari_kerja_rabu' =>
            (bool) Setting::getValue(
                'hari_kerja_rabu',
                '1'
            ),

            'hari_kerja_kamis' =>
            (bool) Setting::getValue(
                'hari_kerja_kamis',
                '1'
            ),

            'hari_kerja_jumat' =>
            (bool) Setting::getValue(
                'hari_kerja_jumat',
                '1'
            ),

            'hari_kerja_sabtu' =>
            (bool) Setting::getValue(
                'hari_kerja_sabtu',
                '0'
            ),

            'hari_kerja_minggu' =>
            (bool) Setting::getValue(
                'hari_kerja_minggu',
                '0'
            ),

            'bobot_kedisiplinan' =>
            Setting::getValue(
                'bobot_kedisiplinan',
                '20'
            ),

            'bobot_kehadiran' =>
            Setting::getValue(
                'bobot_kehadiran',
                '20'
            ),

            'bobot_kinerja' =>
            Setting::getValue(
                'bobot_kinerja',
                '20'
            ),

            'bobot_kompetensi' =>
            Setting::getValue(
                'bobot_kompetensi',
                '20'
            ),

            'bobot_sikap' =>
            Setting::getValue(
                'bobot_sikap',
                '20'
            ),
        ];

        $hariLiburs = HariLibur::query()
            ->where('aktif', true)
            ->orderBy('tanggal')
            ->get();

        return view('admin.settings.index', [
            'settings' => $settings,
            'hariLiburs' => $hariLiburs,
        ]);
    }

    /**
     * Update pengaturan utama.
     */
    public function update(
        UpdateSettingRequest $request
    ): RedirectResponse {
        $data = $request->validated();

        $totalBobot =
            (int) $data['bobot_kedisiplinan']
            + (int) $data['bobot_kehadiran']
            + (int) $data['bobot_kinerja']
            + (int) $data['bobot_kompetensi']
            + (int) $data['bobot_sikap'];

        if ($totalBobot !== 100) {
            return back()
                ->withErrors([
                    'bobot' =>
                    'Total bobot penilaian harus tepat 100%. Saat ini totalnya '
                        . $totalBobot
                        . '%.',
                ])
                ->withInput();
        }

        DB::transaction(function () use (
            $request,
            $data
        ): void {

            /*
            |--------------------------------------------------------------------------
            | Profil Instansi
            |--------------------------------------------------------------------------
            */
            Setting::setValue(
                'nama_instansi',
                $data['nama_instansi'],
                'instansi'
            );

            Setting::setValue(
                'alamat_instansi',
                $data['alamat_instansi'],
                'instansi'
            );

            /*
            |--------------------------------------------------------------------------
            | Jam Kerja
            |--------------------------------------------------------------------------
            */
            Setting::setValue(
                'jam_masuk',
                $data['jam_masuk'],
                'jam_kerja'
            );

            Setting::setValue(
                'jam_pulang_senin_kamis',
                $data['jam_pulang_senin_kamis'],
                'jam_kerja'
            );

            Setting::setValue(
                'jam_pulang_jumat',
                $data['jam_pulang_jumat'],
                'jam_kerja'
            );

            /*
            |--------------------------------------------------------------------------
            | Hari Kerja
            |--------------------------------------------------------------------------
            |
            | Gunakan request->boolean() agar checkbox yang
            | tidak dicentang otomatis menjadi false / 0.
            |--------------------------------------------------------------------------
            */
            $hari = [
                'senin',
                'selasa',
                'rabu',
                'kamis',
                'jumat',
                'sabtu',
                'minggu',
            ];

            foreach ($hari as $namaHari) {

                $key =
                    'hari_kerja_' . $namaHari;

                $value =
                    $request->boolean($key)
                    ? '1'
                    : '0';

                Setting::setValue(
                    $key,
                    $value,
                    'hari_kerja'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Bobot Penilaian
            |--------------------------------------------------------------------------
            */
            Setting::setValue(
                'bobot_kedisiplinan',
                $data['bobot_kedisiplinan'],
                'penilaian'
            );

            Setting::setValue(
                'bobot_kehadiran',
                $data['bobot_kehadiran'],
                'penilaian'
            );

            Setting::setValue(
                'bobot_kinerja',
                $data['bobot_kinerja'],
                'penilaian'
            );

            Setting::setValue(
                'bobot_kompetensi',
                $data['bobot_kompetensi'],
                'penilaian'
            );

            Setting::setValue(
                'bobot_sikap',
                $data['bobot_sikap'],
                'penilaian'
            );
        });

        return redirect()
            ->route('admin.settings.index')
            ->with(
                'success',
                'Pengaturan berhasil diperbarui.'
            );
    }

    /**
     * Tambah hari libur khusus.
     */
    public function storeHariLibur(
        Request $request
    ): RedirectResponse {
        $validated = $request->validate([
            'tanggal' => [
                'required',
                'date',
                'unique:hari_liburs,tanggal',
            ],

            'nama' => [
                'required',
                'string',
                'max:150',
            ],
        ], [
            'tanggal.required' =>
            'Tanggal libur wajib diisi.',

            'tanggal.unique' =>
            'Tanggal tersebut sudah terdaftar sebagai hari libur.',

            'nama.required' =>
            'Nama hari libur wajib diisi.',
        ]);

        HariLibur::create([
            'tanggal' =>
            $validated['tanggal'],

            'nama' =>
            $validated['nama'],

            'aktif' =>
            true,
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with(
                'success',
                'Hari libur berhasil ditambahkan.'
            );
    }

    /**
     * Hapus hari libur khusus.
     */
    public function destroyHariLibur(
        HariLibur $hariLibur
    ): RedirectResponse {
        $hariLibur->delete();

        return redirect()
            ->route('admin.settings.index')
            ->with(
                'success',
                'Hari libur berhasil dihapus.'
            );
    }
}
