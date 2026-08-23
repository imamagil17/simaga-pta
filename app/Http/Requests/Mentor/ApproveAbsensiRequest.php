<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;

class ApproveAbsensiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isMentor() ?? false;
    }

    public function rules(): array
    {
        return [
            'paraf_mentor' => [
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
            'paraf_mentor.required' => 'Paraf mentor wajib dibuat.',
            'paraf_mentor.string' => 'Format paraf mentor tidak valid.',
            'paraf_mentor.starts_with' => 'Paraf mentor harus berasal dari signature pad.',
            'paraf_mentor.max' => 'Ukuran paraf mentor terlalu besar.',
        ];
    }
}