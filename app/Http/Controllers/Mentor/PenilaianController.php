<?php

namespace App\Http\Controllers\Mentor;

use App\Http\Controllers\Controller;
use App\Http\Requests\Mentor\StorePenilaianRequest;
use App\Models\Mahasiswa;
use App\Models\Mentor;
use App\Models\Penempatan;
use App\Models\Penilaian;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PenilaianController extends Controller
{
    /**
     * Ambil mentor yang sedang login.
     */
    protected function getMentor(): Mentor
    {
        $mentor = Mentor::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->first();

        if (! $mentor) {
            abort(
                403,
                'Profil mentor belum tersedia.'
            );
        }

        return $mentor;
    }

    /**
     * Ambil penempatan mahasiswa yang menjadi
     * bimbingan mentor.
     */
    protected function getPenempatan(
        Mentor $mentor,
        Mahasiswa $mahasiswa
    ): Penempatan {
        $penempatan = Penempatan::query()
            ->with([
                'mahasiswa.user',
                'mentor.user',
                'periodeMagang',
                'penilaian',
            ])
            ->where('mahasiswa_id', $mahasiswa->id)
            ->where('mentor_id', $mentor->id)
            ->where('status', 'active')
            ->latest()
            ->first();

        if (! $penempatan) {
            abort(
                403,
                'Mahasiswa bukan bagian dari bimbingan Anda.'
            );
        }

        return $penempatan;
    }

    /**
     * Daftar mahasiswa bimbingan
     * beserta penilaian mereka.
     */
    public function index(): View
    {
        $mentor = $this->getMentor();

        $penempatans = Penempatan::query()
            ->with([
                'mahasiswa.user',
                'periodeMagang',
                'penilaian',
            ])
            ->where('mentor_id', $mentor->id)
            ->where('status', 'active')
            ->whereHas('mahasiswa', function ($query) {
                $query->where('status', 'active');
            })
            ->whereHas('mahasiswa.user', function ($query) {
                $query->where('status', 'active');
            })
            ->orderByDesc('created_at')
            ->get();

        $rekap = [
            'total' => $penempatans->count(),

            'belum_dinilai' => $penempatans
                ->filter(
                    fn($penempatan) =>
                    ! $penempatan->penilaian
                )
                ->count(),

            'draft' => $penempatans
                ->filter(
                    fn($penempatan) =>
                    $penempatan->penilaian?->status === 'draft'
                )
                ->count(),

            'final' => $penempatans
                ->filter(
                    fn($penempatan) =>
                    $penempatan->penilaian?->status === 'final'
                )
                ->count(),
        ];

        return view('mentor.penilaian.index', [
            'mentor' => $mentor,
            'penempatans' => $penempatans,
            'rekap' => $rekap,
        ]);
    }

    /**
     * Form penilaian mahasiswa.
     */
    public function create(
        Mahasiswa $mahasiswa
    ): View {
        $mentor = $this->getMentor();

        $penempatan = $this->getPenempatan(
            $mentor,
            $mahasiswa
        );

        $penilaian = $penempatan->penilaian;

        return view('mentor.penilaian.create', [
            'mentor' => $mentor,
            'penempatan' => $penempatan,
            'penilaian' => $penilaian,
        ]);
    }

    /**
     * Simpan / update penilaian sebagai draft.
     */
    public function store(
        StorePenilaianRequest $request,
        Mahasiswa $mahasiswa
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $penempatan = $this->getPenempatan(
            $mentor,
            $mahasiswa
        );

        if (
            $penempatan->penilaian &&
            $penempatan->penilaian->status === 'final'
        ) {
            return back()->withErrors([
                'penilaian' =>
                'Penilaian yang sudah difinalisasi tidak dapat diubah.',
            ]);
        }

        $validated = $request->validated();

        $penilaianAkhir = round(
            (
                ((float) $validated['nilai_kedisiplinan'] * 0.20) +
                ((float) $validated['nilai_kehadiran'] * 0.20) +
                ((float) $validated['nilai_kinerja'] * 0.20) +
                ((float) $validated['nilai_kompetensi'] * 0.20) +
                ((float) $validated['nilai_sikap'] * 0.20)
            ),
            2
        );

        $penilaian = $penempatan->penilaian;

        if (! $penilaian) {

            $penilaian = Penilaian::create([
                'mahasiswa_id' =>
                $mahasiswa->id,

                'penempatan_id' =>
                $penempatan->id,

                'mentor_id' =>
                $mentor->id,

                'nilai_kedisiplinan' =>
                $validated['nilai_kedisiplinan'],

                'nilai_kehadiran' =>
                $validated['nilai_kehadiran'],

                'nilai_kinerja' =>
                $validated['nilai_kinerja'],

                'nilai_kompetensi' =>
                $validated['nilai_kompetensi'],

                'nilai_sikap' =>
                $validated['nilai_sikap'],

                'nilai_akhir' =>
                $penilaianAkhir,

                'catatan' =>
                $validated['catatan'] ?? null,

                'status' => 'draft',
            ]);
        } else {

            $penilaian->update([
                'nilai_kedisiplinan' =>
                $validated['nilai_kedisiplinan'],

                'nilai_kehadiran' =>
                $validated['nilai_kehadiran'],

                'nilai_kinerja' =>
                $validated['nilai_kinerja'],

                'nilai_kompetensi' =>
                $validated['nilai_kompetensi'],

                'nilai_sikap' =>
                $validated['nilai_sikap'],

                'nilai_akhir' =>
                $penilaianAkhir,

                'catatan' =>
                $validated['catatan'] ?? null,
            ]);
        }

        return redirect()
            ->route(
                'mentor.penilaian.show',
                $mahasiswa
            )
            ->with(
                'success',
                'Penilaian berhasil disimpan sebagai draft.'
            );
    }

    /**
     * Detail penilaian.
     */
    public function show(
        Mahasiswa $mahasiswa
    ): View {
        $mentor = $this->getMentor();

        $penempatan = $this->getPenempatan(
            $mentor,
            $mahasiswa
        );

        $penilaian = $penempatan->penilaian;

        return view('mentor.penilaian.show', [
            'mentor' => $mentor,
            'penempatan' => $penempatan,
            'penilaian' => $penilaian,
        ]);
    }

    /**
     * Finalisasi penilaian.
     */
    public function finalize(
        Mahasiswa $mahasiswa
    ): RedirectResponse {
        $mentor = $this->getMentor();

        $penempatan = $this->getPenempatan(
            $mentor,
            $mahasiswa
        );

        $penilaian = $penempatan->penilaian;

        if (! $penilaian) {
            return back()->withErrors([
                'penilaian' =>
                'Penilaian belum dibuat.',
            ]);
        }

        if ($penilaian->status === 'final') {
            return back()->withErrors([
                'penilaian' =>
                'Penilaian sudah difinalisasi.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Pastikan semua aspek telah diisi.
        |--------------------------------------------------------------------------
        */
        $nilai = [
            $penilaian->nilai_kedisiplinan,
            $penilaian->nilai_kehadiran,
            $penilaian->nilai_kinerja,
            $penilaian->nilai_kompetensi,
            $penilaian->nilai_sikap,
        ];

        if (collect($nilai)->contains(null)) {
            return back()->withErrors([
                'penilaian' =>
                'Semua aspek penilaian harus diisi sebelum difinalisasi.',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Hitung ulang nilai akhir.
        |--------------------------------------------------------------------------
        */
        $nilaiAkhir =
            $penilaian->hitungNilaiAkhir();

        DB::transaction(function () use (
            $penilaian,
            $nilaiAkhir
        ) {
            $penilaian->update([
                'nilai_akhir' =>
                $nilaiAkhir,

                'status' =>
                'final',

                'finalized_by' =>
                Auth::id(),

                'finalized_at' =>
                now(),
            ]);
        });

        return redirect()
            ->route(
                'mentor.penilaian.show',
                $mahasiswa
            )
            ->with(
                'success',
                'Penilaian berhasil difinalisasi.'
            );
    }
}
