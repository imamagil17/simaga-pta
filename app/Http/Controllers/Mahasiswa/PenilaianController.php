<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Penilaian;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class PenilaianController extends Controller
{
    /**
     * Ambil penilaian milik mahasiswa yang sedang login.
     */
    protected function getPenilaian(): ?Penilaian
    {
        $user = Auth::user();

        $mahasiswa = $user->mahasiswa;

        if (! $mahasiswa) {
            abort(403, 'Profil mahasiswa belum tersedia.');
        }

        return Penilaian::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'penempatan.periodeMagang',
            ])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->latest()
            ->first();
    }

    /**
     * Halaman penilaian mahasiswa.
     */
    public function index(): View
    {
        $penilaian = $this->getPenilaian();

        return view('mahasiswa.penilaian.index', [
            'penilaian' => $penilaian,
        ]);
    }

    /**
     * Detail penilaian.
     */
    public function show(): View
    {
        $penilaian = $this->getPenilaian();

        return view('mahasiswa.penilaian.show', [
            'penilaian' => $penilaian,
        ]);
    }
}
