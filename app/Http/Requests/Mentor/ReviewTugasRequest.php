<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isMentor();
    }

    public function rules(): array
    {
        return [
            'nilai' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'catatan_mentor' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nilai' => 'nilai',
            'catatan_mentor' => 'catatan mentor',
        ];
    }
}
