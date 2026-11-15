<?php

namespace App\Http\Controllers;

use App\Models\KategoriPenyaluran;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuperAdminKategoriController extends Controller
{
    private const TIPE_TUJUAN = ['Program Studi', 'Direktorat'];
    private const STATUS_OPTIONS = ['Aktif', 'Nonaktif'];

    /**
     * Ambil user Super Admin yang sedang aktif (fallback ke Super Admin pertama jika belum login).
     */
    protected function getActiveSuperAdmin(): User
    {
        if (Auth::check() && Auth::user()->role === 'Super Admin') {
            return Auth::user();
        }

        try {
            return User::where('role', 'Super Admin')->first() ?? new User([
                'name' => 'Rizky Pratama',
                'role' => 'Super Admin',
            ]);
        } catch (\Throwable) {
            return new User([
                'name' => 'Rizky Pratama',
                'role' => 'Super Admin',
            ]);
        }
    }

    /**
     * Format satu record KategoriPenyaluran untuk response JSON / Alpine.js.
     */
    protected function formatRule(KategoriPenyaluran $rule): array
    {
        return [
            'id' => $rule->id,
            'kategori_kata_kunci' => $rule->kategori_kata_kunci,
            'tipe_tujuan' => $rule->tipe_tujuan,
            'rekomendasi_default' => $rule->rekomendasi_default,
            'status' => $rule->status,
            'urutan' => $rule->urutan,
        ];
    }

    /**
     * Tampilkan halaman Kategori dan Aturan Penyaluran.
     */
    public function index(): View
    {
        $authUser = $this->getActiveSuperAdmin();

        try {
            $rules = KategoriPenyaluran::orderBy('urutan')
                ->orderBy('id')
                ->get()
                ->map(fn (KategoriPenyaluran $r) => $this->formatRule($r))
                ->values()
                ->all();
        } catch (\Throwable) {
            $rules = [];
        }

        return view('superadmin.kategori-penyaluran', compact('authUser', 'rules'));
    }

    /**
     * Simpan aturan penyaluran baru ke database.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'kategori_kata_kunci' => ['required', 'string', 'max:255'],
            'tipe_tujuan' => ['required', Rule::in(self::TIPE_TUJUAN)],
            'rekomendasi_default' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(self::STATUS_OPTIONS)],
        ], [
            'kategori_kata_kunci.required' => 'Kategori / kata kunci wajib diisi.',
            'tipe_tujuan.required' => 'Tipe tujuan wajib dipilih.',
            'tipe_tujuan.in' => 'Tipe tujuan tidak valid.',
            'rekomendasi_default.required' => 'Rekomendasi default wajib diisi.',
            'status.required' => 'Status aturan wajib dipilih.',
            'status.in' => 'Status aturan tidak valid.',
        ]);

        $nextOrder = (KategoriPenyaluran::max('urutan') ?? 0) + 1;

        $rule = KategoriPenyaluran::create([
            'kategori_kata_kunci' => trim($validated['kategori_kata_kunci']),
            'tipe_tujuan' => $validated['tipe_tujuan'],
            'rekomendasi_default' => trim($validated['rekomendasi_default']),
            'status' => $validated['status'],
            'urutan' => $nextOrder,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Aturan penyaluran berhasil ditambahkan.',
                'rule' => $this->formatRule($rule),
            ], 201);
        }

        return redirect()->route('superadmin.kategori')
            ->with('success', 'Aturan penyaluran berhasil ditambahkan.');
    }

    /**
     * Perbarui aturan penyaluran yang ada.
     */
    public function update(Request $request, KategoriPenyaluran $kategoriPenyaluran): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'kategori_kata_kunci' => ['required', 'string', 'max:255'],
            'tipe_tujuan' => ['required', Rule::in(self::TIPE_TUJUAN)],
            'rekomendasi_default' => ['required', 'string', 'max:255'],
            'status' => ['required', Rule::in(self::STATUS_OPTIONS)],
        ], [
            'kategori_kata_kunci.required' => 'Kategori / kata kunci wajib diisi.',
            'tipe_tujuan.required' => 'Tipe tujuan wajib dipilih.',
            'tipe_tujuan.in' => 'Tipe tujuan tidak valid.',
            'rekomendasi_default.required' => 'Rekomendasi default wajib diisi.',
            'status.required' => 'Status aturan wajib dipilih.',
            'status.in' => 'Status aturan tidak valid.',
        ]);

        $kategoriPenyaluran->update([
            'kategori_kata_kunci' => trim($validated['kategori_kata_kunci']),
            'tipe_tujuan' => $validated['tipe_tujuan'],
            'rekomendasi_default' => trim($validated['rekomendasi_default']),
            'status' => $validated['status'],
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Aturan penyaluran berhasil diperbarui.',
                'rule' => $this->formatRule($kategoriPenyaluran->fresh()),
            ]);
        }

        return redirect()->route('superadmin.kategori')
            ->with('success', 'Aturan penyaluran berhasil diperbarui.');
    }

    /**
     * Hapus aturan penyaluran dari database.
     */
    public function destroy(Request $request, KategoriPenyaluran $kategoriPenyaluran): JsonResponse|RedirectResponse
    {
        $kategoriPenyaluran->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Aturan penyaluran berhasil dihapus.',
            ]);
        }

        return redirect()->route('superadmin.kategori')
            ->with('success', 'Aturan penyaluran berhasil dihapus.');
    }
}
