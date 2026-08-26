<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isAdministrator();
    }

    public function rules(): array
    {
        return [
            'nama_instansi' => [
                'required',
                'string',
                'max:150',
            ],

            'alamat_instansi' => [
                'required',
                'string',
                'max:500',
            ],

            'jam_masuk' => [
                'required',
                'date_format:H:i',
            ],

            'jam_pulang_senin_kamis' => [
                'required',
                'date_format:H:i',
            ],

            'jam_pulang_jumat' => [
                'required',
                'date_format:H:i',
            ],

            /*
            |--------------------------------------------------------------------------
            | Hari kerja
            |--------------------------------------------------------------------------
            */
            'hari_kerja_senin' => [
                'sometimes',
                'boolean',
            ],

            'hari_kerja_selasa' => [
                'sometimes',
                'boolean',
            ],

            'hari_kerja_rabu' => [
                'sometimes',
                'boolean',
            ],

            'hari_kerja_kamis' => [
                'sometimes',
                'boolean',
            ],

            'hari_kerja_jumat' => [
                'sometimes',
                'boolean',
            ],

            'hari_kerja_sabtu' => [
                'sometimes',
                'boolean',
            ],

            'hari_kerja_minggu' => [
                'sometimes',
                'boolean',
            ],

            /*
            |--------------------------------------------------------------------------
            | Bobot
            |--------------------------------------------------------------------------
            */
            'bobot_kedisiplinan' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'bobot_kehadiran' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'bobot_kinerja' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'bobot_kompetensi' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],

            'bobot_sikap' => [
                'required',
                'integer',
                'min:0',
                'max:100',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_instansi.required' =>
            'Nama instansi wajib diisi.',

            'alamat_instansi.required' =>
            'Alamat instansi wajib diisi.',

            'jam_masuk.date_format' =>
            'Format jam masuk harus HH:MM.',

            'jam_pulang_senin_kamis.date_format' =>
            'Format jam pulang Senin-Kamis harus HH:MM.',

            'jam_pulang_jumat.date_format' =>
            'Format jam pulang Jumat harus HH:MM.',

            'bobot_kedisiplinan.integer' =>
            'Bobot kedisiplinan harus berupa angka.',

            'bobot_kehadiran.integer' =>
            'Bobot kehadiran harus berupa angka.',

            'bobot_kinerja.integer' =>
            'Bobot kinerja harus berupa angka.',

            'bobot_kompetensi.integer' =>
            'Bobot kompetensi harus berupa angka.',

            'bobot_sikap.integer' =>
            'Bobot sikap harus berupa angka.',
        ];
    }
}
