<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class PusatBantuanController extends Controller
{
    /**
     * Menampilkan halaman Pusat Bantuan CampusVoice.
     */
    public function index(): View
    {
        return view('pusat-bantuan');
    }
}

