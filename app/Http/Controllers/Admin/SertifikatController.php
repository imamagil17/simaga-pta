<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RejectSertifikatRequest;
use App\Models\Sertifikat;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SertifikatController extends Controller
{
    /**
     * Query dasar pengajuan sertifikat.
     */
    protected function sertifikatQuery(Request $request)
    {
        return Sertifikat::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'penempatan.periodeMagang',
                'approver',
            ])

            ->when(
                $request->input('status'),
                function ($query, $status) {
                    $query->where(
                        'status',
                        $status
                    );
                }
            )

            ->when(
                $request->input('search'),
                function ($query, $search) {
                    $query->where(function ($q) use ($search) {

                        $q->whereHas(
                            'mahasiswa.user',
                            function ($userQuery) use ($search) {
                                $userQuery->where(
                                    'name',
                                    'like',
                                    '%' . $search . '%'
                                );
                            }
                        )

                            ->orWhereHas(
                                'mahasiswa',
                                function ($mahasiswaQuery) use ($search) {
                                    $mahasiswaQuery->where(
                                        'nim',
                                        'like',
                                        '%' . $search . '%'
                                    );
                                }
                            );
                    });
                }
            )

            ->orderByDesc('created_at');
    }

    /**
     * Daftar pengajuan sertifikat.
     */
    public function index(Request $request): View
    {
        $sertifikats = $this
            ->sertifikatQuery($request)
            ->get();

        $rekap = [
            'total' => $sertifikats->count(),

            'pending' => $sertifikats
                ->where('status', 'pending')
                ->count(),

            'approved' => $sertifikats
                ->where('status', 'approved')
                ->count(),

            'rejected' => $sertifikats
                ->where('status', 'rejected')
                ->count(),
        ];

        return view('admin.sertifikat.index', [
            'sertifikats' => $sertifikats,
            'rekap' => $rekap,
            'filters' => [
                'status' =>
                $request->input('status'),

                'search' =>
                $request->input('search'),
            ],
        ]);
    }

    /**
     * Detail pengajuan sertifikat.
     */
    public function show(
        Sertifikat $sertifikat
    ): View {
        $sertifikat->load([
            'mahasiswa.user',
            'mentor.user',
            'penempatan.periodeMagang',
            'approver',
        ]);

        return view('admin.sertifikat.show', [
            'sertifikat' => $sertifikat,
        ]);
    }

    /**
     * Menyetujui pengajuan sertifikat.
     */
    public function approve(
        Sertifikat $sertifikat
    ): RedirectResponse {
        if ($sertifikat->status !== 'pending') {
            return back()->withErrors([
                'sertifikat' =>
                'Hanya pengajuan yang masih menunggu yang dapat disetujui.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Nomor sertifikat
        |--------------------------------------------------------------------------
        */
        $nomorSertifikat =
            $this->generateNomorSertifikat(
                $sertifikat
            );

        DB::transaction(function () use (
            $sertifikat,
            $nomorSertifikat
        ) {
            $sertifikat->update([
                'status' =>
                'approved',

                'nomor_sertifikat' =>
                $nomorSertifikat,

                'approved_by' =>
                Auth::id(),

                'approved_at' =>
                now(),

                'catatan' =>
                null,
            ]);
        });

        return redirect()
            ->route(
                'admin.sertifikat.show',
                $sertifikat
            )
            ->with(
                'success',
                'Pengajuan sertifikat berhasil disetujui.'
            );
    }

    /**
     * Menolak pengajuan sertifikat.
     */
    public function reject(
        RejectSertifikatRequest $request,
        Sertifikat $sertifikat
    ): RedirectResponse {
        if ($sertifikat->status !== 'pending') {
            return back()->withErrors([
                'sertifikat' =>
                'Hanya pengajuan yang masih menunggu yang dapat ditolak.',
            ]);
        }

        $sertifikat->update([
            'status' =>
            'rejected',

            'catatan' =>
            $request->validated()['catatan'],

            'approved_by' =>
            Auth::id(),

            'approved_at' =>
            now(),

            'nomor_sertifikat' =>
            null,

            'file_sertifikat' =>
            null,
        ]);

        return redirect()
            ->route(
                'admin.sertifikat.show',
                $sertifikat
            )
            ->with(
                'success',
                'Pengajuan sertifikat ditolak.'
            );
    }

    /**
     * Generate nomor sertifikat.
     *
     * Contoh:
     * 001/SIMAGA/PTA-PALU/VIII/2026
     */
    protected function generateNomorSertifikat(
        Sertifikat $sertifikat
    ): string {
        $tahun = now()->format('Y');

        $bulan = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII',
        ];

        $bulanRomawi =
            $bulan[(int) now()->format('n')];

        /*
        |--------------------------------------------------------------------------
        | Cari nomor terakhir pada tahun berjalan.
        |--------------------------------------------------------------------------
        */
        $lastNumber = Sertifikat::query()
            ->where('status', 'approved')
            ->whereNotNull('nomor_sertifikat')
            ->whereYear(
                'approved_at',
                $tahun
            )
            ->get()
            ->map(function ($item) {

                if (
                    ! $item->nomor_sertifikat
                ) {
                    return 0;
                }

                $parts = explode(
                    '/',
                    $item->nomor_sertifikat
                );

                return isset($parts[0])
                    ? (int) $parts[0]
                    : 0;
            })
            ->max();

        $number =
            ((int) $lastNumber) + 1;

        return str_pad(
            (string) $number,
            3,
            '0',
            STR_PAD_LEFT
        )
            . '/SIMAGA/PTA-PALU/'
            . $bulanRomawi
            . '/'
            . $tahun;
    }
}
