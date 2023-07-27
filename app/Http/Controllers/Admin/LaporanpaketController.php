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
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

use PDF;

class LaporanpaketController extends Controller
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
        // $this->data['currentAdminSubMenu'] = 'report-revenue';

        // $startDate = $request->input('start');
        // $endDate = $request->input('end');

        // if ($startDate && !$endDate) {
        //     \Session::flash('error', 'The end date is required if the start date is present');
        //     return redirect('admin/reports/revenue');
        // }

        // if (!$startDate && $endDate) {
        //     \Session::flash('error', 'The start date is required if the end date is present');
        //     return redirect('admin/reports/revenue');
        // }

        // if ($startDate && $endDate) {
        //     if (strtotime($endDate) < strtotime($startDate)) {
        //         \Session::flash('error', 'The end date should be greater or equal than start date');
        //         return redirect('admin/reports/revenue');
        //     }

        //     $earlier = new \DateTime($startDate);
        //     $later = new \DateTime($endDate);
        //     $diff = $later->diff($earlier)->format("%a");

        //     if ($diff >= 31) {
        //         \Session::flash('error', 'The number of days in the date ranges should be lower or equal to 31 days');
        //         return redirect('admin/reports/revenue');
        //     }
        // } else {
        //     $currentDate = date('Y-m-d');
        //     $startDate = date('Y-m-01', strtotime($currentDate));
        //     $endDate = date('Y-m-t', strtotime($currentDate));
        // }
        // $this->data['startDate'] = $startDate;
        // $this->data['endDate'] = $endDate;

        // $sql = "WITH recursive date_ranges AS (
        //         SELECT :start_date_series AS date
        //         UNION ALL
        //         SELECT date + INTERVAL 1 DAY
        //         FROM date_ranges
        //         WHERE date < :end_date_series
        //         ),
        //         filtered_orders AS (
        //             SELECT *
        //             FROM bookings
        //             WHERE DATE(time_from) >= :start_date
        //                 AND DATE(time_to) <= :end_date
        //                 AND status = :status
        //         )

        //      SELECT
        //          DISTINCT DR.date,
        //          COUNT(FO.id) num_of_orders,
        //          COALESCE(SUM(FO.grand_total),0)
        //      FROM date_ranges DR
        //      LEFT JOIN filtered_orders FO ON DATE(time_from) = DR.date
        //      GROUP BY DR.date
        //      ORDER BY DR.date ASC";

        // $transaksi = \DB::select(
        //     \DB::raw($sql),
        //     [
        //         'time_from' => $startDate,
        //         'end_date_series' => $endDate,
        //         'start_date' => $startDate,
        //         'end_date' => $endDate,
        //         'status' => 2,
        //     ]
        // );

        // $this->data['transaksi'] = ($startDate && $endDate) ? $transaksi : [];

        // if ($exportAs = $request->input('export')) {
        //     if (!in_array($exportAs, ['xlsx', 'pdf'])) {
        //         \Session::flash('error', 'Invalid export request');
        //         return redirect('admin/laporan/transaksi');
        //     }

        //     if ($exportAs == 'xlsx') {
        //         $fileName = 'report-transaksi-' . $startDate . '-' . $endDate . '.xlsx';

        //         return Excel::download(new ReportTransaksiExport($transaksi), $fileName);
        //     }

        //     if ($exportAs == 'pdf') {
        //         $fileName = 'report-transaksi-' . $startDate . '-' . $endDate . '.pdf';
        //         $pdf = PDF::loadView('admin.laporan.exports.transaksi_pdf', $this->data);

        //         return $pdf->download($fileName);
        //     }
        // }

        $data = [
            'bookingpaket' => Bookingpaket::where('status', 2)->get(),
            'totalharga' => Bookingpaket::where('status', 2)->sum('grand_total'),
            'jumlah' => Bookingpaket::where('status', 2)->count(),
            'title' => Auth()->user()->name

        ];

        return view('admin.laporan.indexpaket', $data);
    }

    public function laporanSearch(Request $request)
    {
        $title = Auth()->user()->name;
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $jenis_paket = $request->input('jenis_paket');
        $tanggal = $fromDate . '-' . $toDate;
        $bookingpaket = Bookingpaket::where('status', 2)
            ->where('time_from', '>=', $fromDate)
            ->where('time_to', '<=', $toDate);

        if (!empty($jenis_paket)) {
            $bookingpaket = $bookingpaket->whereHas('services', function ($query) use ($jenis_paket) {
                $query->where('jenis_paket', $jenis_paket);
            });
        }
        $bookingpaket = $bookingpaket->get();

        $totalharga = Bookingpaket::where('status', 2);
        if (!empty($jenis_paket)) {
            $totalharga->whereHas('services', function ($query) use ($jenis_paket) {
                $query->where('jenis_paket', $jenis_paket);
            });
        }
        $totalharga = $totalharga->sum('grand_total');

        $jumlah = Bookingpaket::where('status', 2);
        if (!empty($jenis_paket)) {
            $jumlah->whereHas('services', function ($query) use ($jenis_paket) {
                $query->where('jenis_paket', $jenis_paket);
            });
        }
        $jumlah = $jumlah->count();


        return view('admin.laporan.indexpaket', compact('bookingpaket', 'totalharga', 'jumlah', 'tanggal'));
    }
}
