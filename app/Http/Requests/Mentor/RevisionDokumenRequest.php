<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;

class RevisionDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isMentor();
    }

    public function rules(): array
    {
        return [
            'catatan' => [
                'required',
                'string',
                'min:5',
                'max:5000',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'catatan' => 'catatan revisi',
        ];
    }

    public function messages(): array
    {
        return [
            'catatan.required' =>
            'Catatan revisi wajib diisi.',

            'catatan.min' =>
            'Catatan revisi minimal 5 karakter.',
        ];
    }
}
