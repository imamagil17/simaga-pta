<?php

namespace App\Http\Requests\Mahasiswa;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDokumenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check()
            && auth()->user()->isMahasiswa();
    }

    public function rules(): array
    {
        return [
            'jenis_dokumen' => [
                'required',
                Rule::in([
                    'ktm',
                    'ktp',
                    'cv',
                    'surat_pengantar',
                    'surat_pernyataan',
                    'surat_penempatan',
                    'surat_selesai_magang',
                    'laporan_magang',
                    'lampiran_laporan',
                    'dokumen_pendukung',
                    'dokumen_lainnya',
                ]),
            ],

            'nama_dokumen' => [
                'required',
                'string',
                'max:150',
            ],

            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png',
                'max:10240',
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'jenis_dokumen' => 'jenis dokumen',
            'nama_dokumen' => 'nama dokumen',
            'file' => 'file dokumen',
        ];
    }
}
