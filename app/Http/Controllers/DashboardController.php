<?php

namespace App\Http\Controllers;

use App\Models\AppNotification;
use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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

    public function index(Request $request)
    {
        $authUser = $this->getActiveUser();

        $userId = $authUser->id;

        $totalLaporan = Laporan::where('user_id', $userId)->count();
        $menungguVerifikasi = Laporan::where('user_id', $userId)->where('status', 'Menunggu Verifikasi')->count();
        $sedangDiproses = Laporan::where('user_id', $userId)->whereIn('status', ['Diproses', 'Diteruskan'])->count();
        $laporanSelesai = Laporan::where('user_id', $userId)->where('status', 'Selesai')->count();

        $laporanTerbaru = Laporan::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        $unreadCount = AppNotification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        // Cek jika ada tiket yang dipilih untuk inline detail di dashboard
        $selectedTiket = $request->query('tiket');
        $detailLaporan = null;
        if ($selectedTiket) {
            $detailLaporan = Laporan::with(['tanggapans', 'trackings', 'user'])
                ->where('nomor_tiket', $selectedTiket)
                ->first();
        }

        if (!$detailLaporan && $laporanTerbaru->isNotEmpty()) {
            $detailLaporan = Laporan::with(['tanggapans', 'trackings', 'user'])
                ->find($laporanTerbaru->first()->id);
        }

        return view('dashboard', compact(
            'authUser',
            'totalLaporan',
            'menungguVerifikasi',
            'sedangDiproses',
            'laporanSelesai',
            'laporanTerbaru',
            'unreadCount',
            'detailLaporan'
        ));
    }
}

