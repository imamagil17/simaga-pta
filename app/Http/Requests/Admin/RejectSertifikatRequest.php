<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RejectSertifikatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isAdministrator();
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
            'catatan' => 'catatan penolakan',
        ];
    }

    public function messages(): array
    {
        return [
            'catatan.required' =>
            'Catatan penolakan wajib diisi.',

            'catatan.min' =>
            'Catatan penolakan minimal 5 karakter.',
        ];
    }
}
