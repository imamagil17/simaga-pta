<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsensiPulangRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'paraf_mahasiswa' => [
                'required',
                'string',
                'starts_with:data:image/png;base64,',
                'max:2000000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'paraf_mahasiswa.required' => 'Paraf mahasiswa wajib dibuat.',
            'paraf_mahasiswa.string' => 'Format paraf tidak valid.',
            'paraf_mahasiswa.starts_with' => 'Paraf harus berupa gambar PNG dari signature pad.',
            'paraf_mahasiswa.max' => 'Ukuran data paraf terlalu besar.',
        ];
    }
}