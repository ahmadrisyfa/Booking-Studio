<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notifikasi;
class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::where('to_user', auth()->id())->latest()->get();

        return view('notifikasi',compact('notifikasi'));
    }
}
