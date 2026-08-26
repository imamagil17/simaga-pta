<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;

class RevisionTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isMentor();
    }

    public function rules(): array
    {
        return [
            'catatan_mentor' => [
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
            'catatan_mentor' => 'catatan revisi',
        ];
    }

    public function messages(): array
    {
        return [
            'catatan_mentor.required' =>
            'Catatan revisi wajib diisi.',

            'catatan_mentor.min' =>
            'Catatan revisi minimal 5 karakter.',

            'catatan_mentor.max' =>
            'Catatan revisi maksimal 5000 karakter.',
        ];
    }
}
