<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;

class StorePenilaianRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isMentor();
    }

    public function rules(): array
    {
        return [
            'nilai_kedisiplinan' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'nilai_kehadiran' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'nilai_kinerja' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'nilai_kompetensi' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'nilai_sikap' => [
                'required',
                'numeric',
                'min:0',
                'max:100',
            ],

            'catatan' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'nilai_kedisiplinan' => 'nilai kedisiplinan',
            'nilai_kehadiran' => 'nilai kehadiran',
            'nilai_kinerja' => 'nilai kinerja',
            'nilai_kompetensi' => 'nilai kompetensi',
            'nilai_sikap' => 'nilai sikap',
            'catatan' => 'catatan',
        ];
    }
}
