<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use App\Models\UnitPenangan;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SuperAdminAkunController extends Controller
{
    private const MANAGED_ROLES = ['Admin SSC', 'Petugas Unit', 'Super Admin', 'Mahasiswa'];

    private const DEFAULT_UNIT_OPTIONS = [
        'Student Service Center',
        'Administrasi Sistem',
        'Direktorat Pusat Teknologi Informasi',
        'Direktorat Logistik dan Aset',
        'Direktorat Akademik',
        'Direktorat Kemahasiswaan',
        'Direktorat Keuangan',
        'Direktorat Sumber Daya Manusia',
        'Direktorat Sekretariat & Perencanaan Strategis',
        'Direktorat Bandung Techno Park',
        'Direktorat Pasca Sarjana dan Advance Learning',
        'Direktorat Pemasaran & Admisi',
        'Direktorat Pengembangan Karir, Alumni, & Endowment',
        'Direktorat Kerjasama Strategis & Kantor Urusan Internasional',
        'Direktorat Penelitian dan Pengabdian Masyarakat',
        'SATPRAS (Sarana Prasarana)',
        'Program Studi S1 Sistem Informasi',
        'Program Studi S1 Teknologi Informasi',
        'Program Studi S1 Teknik Informatika',
        'Program Studi S1 Rekayasa Perangkat Lunak',
        'Program Studi S1 Sains Data',
        'Program Studi S1 Teknik Elektro',
        'Program Studi S1 Teknik Industri',
        'Program Studi S1 Teknik Komputer',
        'Program Studi S1 Teknik Logistik',
        'Program Studi S1 Bisnis Digital',
        'Program Studi S1 Teknik Telekomunikasi',
    ];

    private const DEFAULT_PRODI_OPTIONS = [
        'S1 Sistem Informasi',
        'S1 Teknologi Informasi',
        'S1 Teknik Informatika',
        'S1 Rekayasa Perangkat Lunak',
        'S1 Sains Data',
        'S1 Teknik Elektro',
        'S1 Teknik Industri',
        'S1 Teknik Komputer',
        'S1 Teknik Logistik',
        'S1 Bisnis Digital',
        'S1 Teknik Telekomunikasi',
    ];

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
     * Cari record UnitPenangan yang paling sesuai dengan nama unit/prodi.
     */
    protected function resolveUnitPenangan(string $unitName): ?UnitPenangan
    {
        try {
            return UnitPenangan::where('nama_unit', $unitName)->first()
                ?? UnitPenangan::where('nama_unit', 'Program Studi ' . $unitName)->first()
                ?? UnitPenangan::where('nama_unit', 'like', '%' . $unitName . '%')->first();
        } catch (\Throwable) {
            return null;
        }
    }

    /**
     * Kembalikan respons error (JSON atau Redirect) secara konsisten.
     */
    protected function errorResponse(Request $request, string $message, int $status = 422): JsonResponse|RedirectResponse
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message,
            ], $status);
        }

        return redirect()
            ->route('superadmin.akun')
            ->with('error', $message);
    }

    /**
     * Format satu record User staf menjadi payload array untuk tampilan Manajemen Akun.
     */
    protected function formatAccount(User $user): array
    {
        $lastActivity = 'Baru saja';
        if ($user->updated_at) {
            $updated = $user->updated_at->wib();
            $lastActivity = $updated->gt(now()->wib()->subDay())
                ? $updated->diffForHumans()
                : $updated->isoFormat('D MMM YYYY');
        }

        $unit = $user->role === 'Mahasiswa'
            ? ($user->prodi ?: ($user->fakultas ?: '-'))
            : ($user->fakultas ?: ($user->prodi ?: '-'));

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'nip' => $user->nim ?? '',
            'role' => $user->role,
            'unit' => $unit,
            'phone' => $user->phone ?? '',
            'status' => $user->status_akun ?: 'Aktif',
            'last_activity' => $lastActivity,
        ];
    }

    /**
     * Tampilkan halaman Manajemen Akun Super Admin.
     */
    public function index(): View
    {
        $authUser = $this->getActiveSuperAdmin();
        $unitOptions = self::DEFAULT_UNIT_OPTIONS;
        $prodiOptions = self::DEFAULT_PRODI_OPTIONS;

        try {
            $users = User::whereIn('role', self::MANAGED_ROLES)
                ->orderBy('id')
                ->get();

            $accounts = $users->map(fn (User $u) => $this->formatAccount($u))->values()->all();

            $dbUnits = UnitPenangan::orderBy('id')->get();
            if ($dbUnits->isNotEmpty()) {
                $unitOptions = collect($unitOptions)
                    ->merge($dbUnits->pluck('nama_unit'))
                    ->unique()
                    ->values()
                    ->all();

                $dbProdi = $dbUnits->where('tipe', 'Program Studi')
                    ->map(fn (UnitPenangan $u) => preg_replace('/^Program Studi\s+/i', '', $u->nama_unit));

                $prodiOptions = collect($prodiOptions)
                    ->merge($dbProdi)
                    ->unique()
                    ->values()
                    ->all();
            }

            // Pastikan seluruh unit/prodi yang sudah terpakai di akun existing selalu ada di opsi dropdown
            $existingStaffUnits = collect($accounts)
                ->where('role', '!=', 'Mahasiswa')
                ->pluck('unit')
                ->filter(fn ($u) => $u && $u !== '-');

            $existingStudentProdis = collect($accounts)
                ->where('role', 'Mahasiswa')
                ->pluck('unit')
                ->filter(fn ($u) => $u && $u !== '-');

            $unitOptions = collect($unitOptions)->merge($existingStaffUnits)->unique()->values()->all();
            $prodiOptions = collect($prodiOptions)->merge($existingStudentProdis)->unique()->values()->all();
        } catch (\Throwable) {
            $accounts = [];
        }

        return view('superadmin.manajemen-akun', compact('authUser', 'accounts', 'unitOptions', 'prodiOptions'));
    }

    /**
     * Simpan akun staf baru ke database.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'nip' => ['nullable', 'string', 'max:50'],
            'role' => ['required', Rule::in(self::MANAGED_ROLES)],
            'unit' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'status_akun' => ['required', Rule::in(['Aktif', 'Nonaktif'])],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email institusi wajib diisi.',
            'email.email' => 'Format email institusi tidak valid.',
            'email.unique' => 'Email institusi sudah terdaftar.',
            'role.required' => 'Peran staf wajib dipilih.',
            'unit.required' => 'Unit penugasan wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
        ]);

        $isMahasiswa = $validated['role'] === 'Mahasiswa';
        $matchedUnit = $this->resolveUnitPenangan($validated['unit']);
        $cleanProdi = $isMahasiswa
            ? preg_replace('/^Program Studi\s+/i', '', $validated['unit'])
            : $validated['unit'];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nim' => $validated['nip'] ?: null,
            'prodi' => $cleanProdi,
            'fakultas' => $isMahasiswa ? 'Direktorat Kampus Surabaya' : $validated['unit'],
            'unit_penangan_id' => $matchedUnit?->id,
            'phone' => $validated['phone'] ?: null,
            'role' => $validated['role'],
            'status_akun' => $validated['status_akun'],
            'password' => Hash::make($validated['password'] ?: '12345678'),
            'email_verified_at' => now(),
        ]);

        $activeAdmin = $this->getActiveSuperAdmin();
        LogAktivitas::catat(
            aktivitas: "Membuat akun baru: {$user->name} ({$user->role})",
            recordTerkait: $user->email,
            kategori: 'Manajemen Akun',
            status: 'Berhasil',
            user: $activeAdmin,
            ipAddress: $request->ip()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Akun ' . $user->name . ' berhasil ditambahkan.',
                'account' => $this->formatAccount($user),
            ], 201);
        }

        return redirect()
            ->route('superadmin.akun')
            ->with('success', 'Akun ' . $user->name . ' berhasil ditambahkan.');
    }

    /**
     * Perbarui data dan status akses akun staf.
     */
    public function update(Request $request, User $user): JsonResponse|RedirectResponse
    {
        if (!in_array($user->role, self::MANAGED_ROLES, true)) {
            abort(403, 'Role akun tidak diizinkan untuk dikelola.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'nip' => ['nullable', 'string', 'max:50'],
            'role' => ['required', Rule::in(self::MANAGED_ROLES)],
            'unit' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:25'],
            'status_akun' => ['required', Rule::in(['Aktif', 'Nonaktif'])],
            'password' => ['nullable', 'string', 'min:8'],
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email institusi wajib diisi.',
            'email.email' => 'Format email institusi tidak valid.',
            'email.unique' => 'Email institusi sudah digunakan akun lain.',
            'role.required' => 'Peran staf wajib dipilih.',
            'unit.required' => 'Unit penugasan wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
        ]);

        $activeAdmin = $this->getActiveSuperAdmin();
        $isSelf = (Auth::check() && Auth::id() === $user->id)
            || ($activeAdmin && $activeAdmin->id && $activeAdmin->id === $user->id);

        if ($isSelf) {
            if ($validated['status_akun'] !== 'Aktif') {
                return $this->errorResponse($request, 'Tidak dapat menonaktifkan akun Super Admin yang sedang digunakan.');
            }
            if ($validated['role'] !== 'Super Admin') {
                return $this->errorResponse($request, 'Tidak dapat mengubah peran akun Super Admin yang sedang digunakan.');
            }
        }

        if ($user->role === 'Super Admin' && ($validated['role'] !== 'Super Admin' || $validated['status_akun'] !== 'Aktif')) {
            $otherActiveSuperAdmins = User::where('role', 'Super Admin')
                ->where('status_akun', 'Aktif')
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherActiveSuperAdmins === 0) {
                return $this->errorResponse($request, 'Sistem harus memiliki minimal satu akun Super Admin yang berstatus Aktif.');
            }
        }

        $isMahasiswa = $validated['role'] === 'Mahasiswa';
        $matchedUnit = $this->resolveUnitPenangan($validated['unit']);
        $cleanProdi = $isMahasiswa
            ? preg_replace('/^Program Studi\s+/i', '', $validated['unit'])
            : $validated['unit'];

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nim' => $validated['nip'] ?: null,
            'prodi' => $cleanProdi,
            'fakultas' => $isMahasiswa ? 'Direktorat Kampus Surabaya' : $validated['unit'],
            'unit_penangan_id' => $matchedUnit?->id,
            'phone' => $validated['phone'] ?: null,
            'role' => $validated['role'],
            'status_akun' => $validated['status_akun'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);
        $user->refresh();

        LogAktivitas::catat(
            aktivitas: "Memperbarui data akun: {$user->name} ({$user->role})",
            recordTerkait: $user->email,
            kategori: 'Manajemen Akun',
            status: 'Berhasil',
            user: $activeAdmin,
            ipAddress: $request->ip()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Perubahan akun ' . $user->name . ' berhasil disimpan.',
                'account' => $this->formatAccount($user),
            ]);
        }

        return redirect()
            ->route('superadmin.akun')
            ->with('success', 'Perubahan akun ' . $user->name . ' berhasil disimpan.');
    }

    /**
     * Hapus akun staf.
     */
    public function destroy(Request $request, User $user): JsonResponse|RedirectResponse
    {
        if (!in_array($user->role, self::MANAGED_ROLES, true)) {
            abort(403, 'Role akun tidak diizinkan untuk dikelola.');
        }

        $activeAdmin = $this->getActiveSuperAdmin();
        $isSelf = (Auth::check() && Auth::id() === $user->id)
            || ($activeAdmin && $activeAdmin->id && $activeAdmin->id === $user->id);

        if ($isSelf) {
            return $this->errorResponse($request, 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($user->role === 'Super Admin') {
            $otherActiveSuperAdmins = User::where('role', 'Super Admin')
                ->where('status_akun', 'Aktif')
                ->where('id', '!=', $user->id)
                ->count();

            if ($otherActiveSuperAdmins === 0) {
                return $this->errorResponse($request, 'Tidak dapat menghapus satu-satunya akun Super Admin yang aktif.');
            }
        }

        $deletedName = $user->name;
        $deletedEmail = $user->email;
        $deletedRole = $user->role;
        $user->delete();

        LogAktivitas::catat(
            aktivitas: "Menghapus akun: {$deletedName} ({$deletedRole})",
            recordTerkait: $deletedEmail,
            kategori: 'Manajemen Akun',
            status: 'Berhasil',
            user: $activeAdmin,
            ipAddress: $request->ip()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Akun ' . $deletedName . ' berhasil dihapus.',
            ]);
        }

        return redirect()
            ->route('superadmin.akun')
            ->with('success', 'Akun ' . $deletedName . ' berhasil dihapus.');
    }
}

