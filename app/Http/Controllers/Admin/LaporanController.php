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

class LaporanController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->data['currentAdminMenu'] = 'report';

        $this->data['exports'] = [
            'xlsx' => 'Excel File',
            'pdf' => 'PDF File',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request request params
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $data = [
            'bookings' => Booking::where('status', 2)->get(),
            
        ];

        return view('admin.laporan.index', $data);
    }
}
