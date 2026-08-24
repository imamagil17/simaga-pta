<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class StoreLogbookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'judul_kegiatan' => [
                'required',
                'string',
                'max:150',
            ],

            'uraian_kegiatan' => [
                'required',
                'string',
                'min:10',
            ],

            'hasil_kegiatan' => [
                'nullable',
                'string',
            ],

            'kendala' => [
                'nullable',
                'string',
            ],

            'rencana_tindak_lanjut' => [
                'nullable',
                'string',
            ],

            'bukti_kegiatan' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'judul_kegiatan' => 'judul kegiatan',
            'uraian_kegiatan' => 'uraian kegiatan',
            'hasil_kegiatan' => 'hasil kegiatan',
            'kendala' => 'kendala',
            'rencana_tindak_lanjut' => 'rencana tindak lanjut',
            'bukti_kegiatan' => 'bukti kegiatan',
        ];
    }
}
