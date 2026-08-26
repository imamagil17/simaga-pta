<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;

class StorePengumpulanTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'jawaban' => [
                'nullable',
                'string',
                'min:5',
            ],

            'file_jawaban' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,rar',
                'max:10240',
            ],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $jawaban = trim((string) $this->input('jawaban'));

            if (
                $jawaban === '' &&
                ! $this->hasFile('file_jawaban')
            ) {
                $validator->errors()->add(
                    'jawaban',
                    'Isi jawaban atau unggah file jawaban.'
                );
            }
        });
    }

    public function attributes(): array
    {
        return [
            'jawaban' => 'jawaban',
            'file_jawaban' => 'file jawaban',
        ];
    }
}
