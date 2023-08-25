<?php



namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Laporan;
use App\Models\Booking;
use App\Models\Bookingpaket;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookingpaketRequest;
use App\Exports\ReportTransaksiExport;
use Maatwebsite\Excel\Facades\Excel;

use PDF;

class LaporanBokingController extends Controller
{
    public function index()
    {
        $data = [
            'bookings' => booking::where('status', 2)->get(),
            'totalharga' => booking::where('status', 2)->sum('grand_total'),
            'jumlah' => booking::where('status', 2)->count(),
            'title' => Auth()->user()->name

        ];

        return view('admin.laporan.index', $data);
    }
    public function laporanSearch(Request $request)
    {
        $title = Auth()->user()->name;
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $bookings = booking::where('status', 2)->where('time_from', '>=', $fromDate)
            ->where('time_to', '<=', $toDate)
            ->get();
        $totalharga = $bookings->sum('grand_total');
        $jumlah = $bookings->count();

        return view('admin.laporan.index', compact('bookings', 'totalharga', 'jumlah'));
    }
}
