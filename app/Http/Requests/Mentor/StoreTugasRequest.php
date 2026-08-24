<?php

namespace App\Http\Requests\Mentor;

use Illuminate\Foundation\Http\FormRequest;

class StoreTugasRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isMentor();
    }

    public function rules(): array
    {
        return [
            'penempatan_id' => [
                'required',
                'integer',
                'exists:penempatans,id',
            ],

            'judul' => [
                'required',
                'string',
                'max:150',
            ],

            'deskripsi' => [
                'required',
                'string',
                'min:10',
            ],

            'tanggal_mulai' => [
                'required',
                'date',
            ],

            'tanggal_deadline' => [
                'required',
                'date',
                'after_or_equal:tanggal_mulai',
            ],

            'file_tugas' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
                'max:10240',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'penempatan_id' => 'mahasiswa',
            'judul' => 'judul tugas',
            'deskripsi' => 'deskripsi',
            'tanggal_mulai' => 'tanggal mulai',
            'tanggal_deadline' => 'tanggal deadline',
            'file_tugas' => 'file tugas',
        ];
    }
}