<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePeriodeMagangRequest;
use App\Http\Requests\Admin\UpdatePeriodeMagangRequest;
use App\Models\PeriodeMagang;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PeriodeMagangController extends Controller
{
    /**
     * Menampilkan daftar periode magang.
     */
    public function index(): View
    {
        $periodes = PeriodeMagang::query()
            ->orderByDesc('tanggal_mulai')
            ->orderBy('nama_periode')
            ->get();

        return view('admin.periode-magangs.index', compact('periodes'));
    }

    /**
     * Menampilkan form tambah periode magang.
     */
    public function create(): View
    {
        return view('admin.periode-magangs.create');
    }

    /**
     * Menyimpan periode magang baru.
     */
    public function store(StorePeriodeMagangRequest $request): RedirectResponse
    {
        PeriodeMagang::create($request->validated());

        return redirect()
            ->route('admin.periode-magangs.index')
            ->with('success', 'Periode magang berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit periode magang.
     */
    public function edit(PeriodeMagang $periodeMagang): View
    {
        return view('admin.periode-magangs.edit', compact('periodeMagang'));
    }

    /**
     * Memperbarui periode magang.
     */
    public function update(
        UpdatePeriodeMagangRequest $request,
        PeriodeMagang $periodeMagang
    ): RedirectResponse {
        $periodeMagang->update($request->validated());

        return redirect()
            ->route('admin.periode-magangs.index')
            ->with('success', 'Periode magang berhasil diperbarui.');
    }
}