<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
class LaporanPenyewaController extends Controller
{
    public function index()
    {
        $user = User::all();
        $jumlah = User::count();

        return view('admin.laporan.penyewa',compact('user','jumlah'));
    }
    public function laporanSearch(Request $request)
    {
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $user = User::where('created_at', '>=', $fromDate)
            ->where('created_at', '<=', $toDate)
            ->get();
        // $totalharga = booking::where('status', 2)->sum('grand_total');
        $jumlah = $user->count();

        return view('admin.laporan.penyewa', compact('user','jumlah'));
    }
}
