<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;

class RejectAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMentor() ?? false;
    }

    public function rules(): array
    {
        return [
            'alasan_penolakan' => [
                'required',
                'string',
                'max:1000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_penolakan.required' => 'Alasan penolakan wajib diisi.',
            'alasan_penolakan.max' => 'Alasan penolakan maksimal 1000 karakter.',
        ];
    }
}