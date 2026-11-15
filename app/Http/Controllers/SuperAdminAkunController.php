<?php

namespace App\Http\Controllers;

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

        try {
            $users = User::whereIn('role', self::MANAGED_ROLES)
                ->orderBy('id')
                ->get();

            $accounts = $users->map(fn (User $u) => $this->formatAccount($u))->values()->all();
        } catch (\Throwable) {
            $accounts = [];
        }

        return view('superadmin.manajemen-akun', compact('authUser', 'accounts'));
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

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nim' => $validated['nip'] ?: null,
            'prodi' => $validated['unit'],
            'fakultas' => $isMahasiswa ? 'Direktorat Kampus Surabaya' : $validated['unit'],
            'phone' => $validated['phone'] ?: null,
            'role' => $validated['role'],
            'status_akun' => $validated['status_akun'],
            'password' => Hash::make($validated['password'] ?: '12345678'),
            'email_verified_at' => now(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Akun staf ' . $user->name . ' berhasil ditambahkan.',
                'account' => $this->formatAccount($user),
            ], 201);
        }

        return redirect()
            ->route('superadmin.akun')
            ->with('success', 'Akun staf ' . $user->name . ' berhasil ditambahkan.');
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

        $isMahasiswa = $validated['role'] === 'Mahasiswa';

        $payload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'nim' => $validated['nip'] ?: null,
            'prodi' => $validated['unit'],
            'fakultas' => $isMahasiswa ? 'Direktorat Kampus Surabaya' : $validated['unit'],
            'phone' => $validated['phone'] ?: null,
            'role' => $validated['role'],
            'status_akun' => $validated['status_akun'],
        ];

        if (!empty($validated['password'])) {
            $payload['password'] = Hash::make($validated['password']);
        }

        $user->update($payload);
        $user->refresh();

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

        if (Auth::check() && Auth::id() === $user->id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tidak dapat menghapus akun yang sedang digunakan.',
                ], 422);
            }

            return redirect()
                ->route('superadmin.akun')
                ->with('error', 'Tidak dapat menghapus akun yang sedang digunakan.');
        }

        $deletedName = $user->name;
        $user->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Akun staf ' . $deletedName . ' berhasil dihapus.',
            ]);
        }

        return redirect()
            ->route('superadmin.akun')
            ->with('success', 'Akun staf ' . $deletedName . ' berhasil dihapus.');
    }
}

