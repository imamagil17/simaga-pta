<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePenempatanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'mahasiswa_id' => [
                'required',
                'integer',
                Rule::exists('mahasiswas', 'id')
                    ->where(fn ($query) => $query->where('status', 'active')),
            ],

            'periode_magang_id' => [
                'required',
                'integer',
                Rule::exists('periode_magangs', 'id')
                    ->where(fn ($query) => $query->where('status', 'active')),
            ],

            'mentor_id' => [
                'required',
                'integer',
                Rule::exists('mentors', 'id')
                    ->where(fn ($query) => $query->where('status', 'active')),
            ],

            'status' => [
                'required',
                'in:active,inactive',
            ],

            'tanggal_mulai' => [
                'nullable',
                'date',
            ],

            'tanggal_selesai' => [
                'nullable',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'mahasiswa_id.required' => 'Mahasiswa wajib dipilih.',
            'mahasiswa_id.exists' => 'Mahasiswa yang dipilih tidak tersedia atau tidak aktif.',

            'periode_magang_id.required' => 'Periode magang wajib dipilih.',
            'periode_magang_id.exists' => 'Periode magang yang dipilih tidak tersedia atau tidak aktif.',

            'mentor_id.required' => 'Mentor wajib dipilih.',
            'mentor_id.exists' => 'Mentor yang dipilih tidak tersedia atau tidak aktif.',

            'status.required' => 'Status penempatan wajib dipilih.',
            'status.in' => 'Status penempatan tidak valid.',

            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',

            'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',

            'keterangan.max' => 'Keterangan maksimal 2000 karakter.',
        ];
    }
}