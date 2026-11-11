<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\AppNotification;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected function getActiveUser(): User
    {
        if (Auth::check()) {
            return Auth::user();
        }

        if (request()->filled('user_id')) {
            $u = User::find(request('user_id'));
            if ($u) return $u;
        }

        if (request()->filled('nim')) {
            $u = User::where('nim', request('nim'))->first();
            if ($u) return $u;
        }

        return User::first() ?? new User();
    }

    /**
     * Display the campus voice custom profile page.
     */
    public function show(Request $request): View
    {
        $authUser = $this->getActiveUser();

        if ($request->routeIs('admin.profil') || $request->is('admin/*') || $authUser->role === 'Admin SSC') {
            if ($authUser->role !== 'Admin SSC') {
                $authUser = User::where('role', 'Admin SSC')->first() ?? $authUser;
            }

            $unreadCount = AppNotification::where('user_id', $authUser->id)
                ->where('is_read', false)
                ->count();

            return view('admin.profil', compact('authUser', 'unreadCount'));
        }

        if ($request->routeIs('unit.profil') || $request->is('unit/*') || $authUser->role === 'Petugas Unit') {
            if ($authUser->role !== 'Petugas Unit') {
                $authUser = User::where('role', 'Petugas Unit')->first() ?? $authUser;
            }

            $unreadCount = AppNotification::where('user_id', $authUser->id)
                ->where('is_read', false)
                ->count();

            return view('unit.profil', compact('authUser', 'unreadCount'));
        }

        $unreadCount = AppNotification::where('user_id', $authUser->id)
            ->where('is_read', false)
            ->count();

        return view('profil', compact('authUser', 'unreadCount'));
    }

    /**
     * Update the user's phone number.
     */
    public function updatePhone(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:25',
        ], [
            'phone.required' => 'Nomor telepon wajib diisi.',
        ]);

        $user = $this->getActiveUser();
        if ($user) {
            $user->phone = $request->phone;
            $user->save();
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Nomor telepon berhasil diperbarui.',
                'phone' => $user->phone,
            ]);
        }

        return redirect()->back()->with('success', 'Nomor telepon berhasil diperbarui.');
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
