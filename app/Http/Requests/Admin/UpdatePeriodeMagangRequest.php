<?php

namespace App\Http\Requests\Admin;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePeriodeMagangRequest extends FormRequest
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
            'nama_periode' => [
                'required',
                'string',
                'max:255',
            ],

            'kode_periode' => [
                'required',
                'string',
                'max:100',
                Rule::unique('periode_magangs', 'kode_periode')
                    ->ignore($this->route('periodeMagang')),
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_selesai' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
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
            'nama_periode.required' => 'Nama periode wajib diisi.',
            'nama_periode.max' => 'Nama periode maksimal 255 karakter.',

            'kode_periode.required' => 'Kode periode wajib diisi.',
            'kode_periode.unique' => 'Kode periode sudah digunakan oleh periode lain.',
            'kode_periode.max' => 'Kode periode maksimal 100 karakter.',

            'tanggal_mulai.required' => 'Tanggal mulai wajib diisi.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',

            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',

            'status.required' => 'Status periode wajib dipilih.',
            'status.in' => 'Status periode tidak valid.',

            'keterangan.max' => 'Keterangan maksimal 2000 karakter.',
        ];
    }
}