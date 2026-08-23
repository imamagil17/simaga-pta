<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMentorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nip' => ['nullable', 'string', 'max:50'],

            'jabatan' => ['nullable', 'string', 'max:255'],

            'bagian' => ['nullable', 'string', 'max:255'],

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

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'nip.max' => 'NIP maksimal 50 karakter.',

            'jabatan.max' => 'Jabatan maksimal 255 karakter.',

            'bagian.max' => 'Bagian maksimal 255 karakter.',

            'jenis_kelamin.in' => 'Jenis kelamin yang dipilih tidak valid.',

            'agama.in' => 'Agama yang dipilih tidak valid.',

            'no_hp.max' => 'Nomor HP maksimal 30 karakter.',

            'foto.file' => 'File foto tidak valid.',

            'foto.image' => 'File foto harus berupa gambar.',

            'foto.mimes' => 'Foto harus berformat JPG, JPEG, PNG, atau WebP.',

            'foto.max' => 'Ukuran foto maksimal 2 MB.',

            'status.required' => 'Status profil wajib dipilih.',

            'status.in' => 'Status profil tidak valid.',

            'keterangan.max' => 'Keterangan maksimal 2000 karakter.',
        ];
    }
}