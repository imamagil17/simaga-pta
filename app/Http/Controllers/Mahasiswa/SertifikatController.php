<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Sertifikat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class SertifikatController extends Controller
{
    /**
     * Ambil sertifikat milik mahasiswa yang sedang login.
     */
    protected function getSertifikat(): ?Sertifikat
    {
        $user = Auth::user();

        if (! $user->mahasiswa) {
            abort(
                403,
                'Profil mahasiswa belum tersedia.'
            );
        }

        return Sertifikat::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'penempatan.periodeMagang',
                'approver',
            ])
            ->where(
                'mahasiswa_id',
                $user->mahasiswa->id
            )
            ->where('status', 'approved')
            ->latest('approved_at')
            ->first();
    }

    /**
     * Halaman sertifikat mahasiswa.
     */
    public function index(): View
    {
        $sertifikat = $this->getSertifikat();

        return view('mahasiswa.sertifikat.index', [
            'sertifikat' => $sertifikat,
        ]);
    }

    /**
     * Detail sertifikat.
     */
    public function show(): View
    {
        $sertifikat = $this->getSertifikat();

        return view('mahasiswa.sertifikat.show', [
            'sertifikat' => $sertifikat,
        ]);
    }

    /**
     * Download sertifikat dalam bentuk PDF.
     */
    public function download(): Response
    {
        $sertifikat = $this->getSertifikat();

        if (! $sertifikat) {
            abort(
                404,
                'Sertifikat belum tersedia.'
            );
        }

        $pdf = Pdf::loadView(
            'mahasiswa.sertifikat.pdf',
            [
                'sertifikat' => $sertifikat,
            ]
        )
            ->setPaper('A4', 'landscape');

        $filename =
            'sertifikat-magang-' .
            str_replace(
                ' ',
                '-',
                strtolower(
                    $sertifikat->mahasiswa->user->name
                )
            ) .
            '.pdf';

        return $pdf->download($filename);
    }
}
