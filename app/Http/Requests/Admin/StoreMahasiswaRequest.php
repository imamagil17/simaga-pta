<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreMahasiswaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nim' => [
                'required',
                'string',
                'max:50',
                'unique:mahasiswas,nim',
            ],

            'perguruan_tinggi' => [
                'required',
                'string',
                'max:255',
            ],

            'program_studi' => [
                'required',
                'string',
                'max:255',
            ],

            'jenis_kelamin' => [
                'nullable',
                'in:Laki-laki,Perempuan',
            ],

            'agama' => [
                'nullable',
                'in:Islam,Kristen Protestan,Kristen Katolik,Hindu,Buddha,Konghucu',
            ],

            'no_hp' => [
                'nullable',
                'string',
                'max:30',
            ],

            'alamat' => [
                'nullable',
                'string',
                'max:1000',
            ],

            'foto' => [
                'nullable',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nim.required' => 'NIM wajib diisi.',
            'nim.unique' => 'NIM sudah digunakan.',
            'nim.max' => 'NIM maksimal 50 karakter.',

            'perguruan_tinggi.required' => 'Perguruan tinggi wajib diisi.',

            'program_studi.required' => 'Program studi wajib diisi.',

            'jenis_kelamin.in' => 'Jenis kelamin tidak valid.',

            'agama.in' => 'Agama tidak valid.',

            'no_hp.max' => 'Nomor HP maksimal 30 karakter.',

            'alamat.max' => 'Alamat maksimal 1000 karakter.',

            'foto.image' => 'File foto harus berupa gambar.',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WebP.',
            'foto.max' => 'Ukuran foto maksimal 2 MB.',

            'status.required' => 'Status profil wajib dipilih.',
            'status.in' => 'Status profil tidak valid.',

            'keterangan.max' => 'Keterangan maksimal 2000 karakter.',
        ];
    }
}