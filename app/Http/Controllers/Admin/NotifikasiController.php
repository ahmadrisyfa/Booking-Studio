<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
class NotifikasiController extends Controller
{
    public function index()
    {
        $notifikasi = Notifikasi::latest()->get();
        return view('admin.notifikasi.index',compact('notifikasi'));
    }
}
