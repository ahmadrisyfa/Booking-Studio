<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\User;
use App\Models\services;
use App\Models\Bookingpaket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookingpaketRequest;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Models\Notifikasi;
class BookingpaketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function nomat($user)
    {

        $tgl = date('y-m-d');
        $number = Bookingpaket::where('created_at', 'like', '%' . $tgl . '%')->count();
        $angka = $number + 1;
        $codes = str_pad($angka, 1, rand(11, 99), STR_PAD_LEFT);
        // $code = 'SIM-' . date('ymd') . $codes . Str::random(15);
        $words = explode(' ', $user);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper($word[0]);
        }
        $code = 'PKT' . date('Ymd') . $initials . Str::random(2);
        return $code;
    }


    public function index()
    {
        abort_if(Gate::denies('bookingpaket_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $bookingpaket = Bookingpaket::with('services')->get();
        $bphariini = Bookingpaket::whereDate('created_at', now())->get();

        // dd($bookingpaket);
        return view('admin.bookingpaket.index', compact('bookingpaket', 'bphariini'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies('bookingpaket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $services = services::where('status', 1)->get();
        $servicesString = $request->get('name');

        return view('admin.bookingpaket.create', compact('services', 'servicesString'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // abort_if(Gate::denies('bookingpaket_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $services = Services::findOrFail($request->services_id);


        $startTime = $request->time_from; // Jam mulai booking dalam format datetime
        $endTime = $request->time_to;
        // $services_id = $request->services_id;
        $bookingExists = DB::table('bookings')
        ->where(function ($query) use ($startTime, $endTime) {
            $query->where(function ($query) use ($startTime, $endTime) {
                $query->where('time_to', '>=', $startTime)
                    ->where('time_from', '<=', $endTime);
                    // ->whereDate('time_to', now());
            });
        })
        ->exists();
        // $services_id = $request->services_id;
    $bookingPaketsExists = DB::table('bookingpakets')
    ->where(function ($query) use ($startTime, $endTime) {
        $query->where(function ($query) use ($startTime, $endTime) {
            $query->where('time_to', '>=', $startTime)
                ->where('time_from', '<=', $endTime);
                // ->whereDate('time_to', now());
        });
    })
    ->exists();
    $EventExists = DB::table('event')
    ->where(function ($query) use ($startTime, $endTime) {
        $query->where(function ($query) use ($startTime, $endTime) {
            $query->where('time_to', '>=', $startTime)
                ->where('time_from', '<=', $endTime);
                // ->whereDate('time_to', now());
        });
    })
    ->exists();
    if ($bookingExists || $bookingPaketsExists || $EventExists) {
        // Ada booking lain pada waktu yang sama
        return redirect()->back()->with([
            'message' => 'Maaf, waktu tersebut sudah dipesan oleh orang lain.',
            'alert-type' => 'danger'
        ]);
    } else {
            $startTime = $request->time_from; // Jam mulai booking dalam format datetime
            $endTime = $request->time_to; // Jam selesai booking dalam format datetime

            $startDateTime = new DateTime($startTime);
            $endDateTime = new DateTime($endTime);

            $interval = $startDateTime->diff($endDateTime);
            $hours = $interval->h;
            if ($services->jam_paket != '') {
                if ($hours >= $services->jam_paket) {

                    return redirect()->back()->with([
                        'message' => 'Maaf, Jam tidak bisa melebihi jam paket',
                        'alert-type' => 'danger'
                    ]);
                }
            }
            if ($services->jenis_paket == "Paket Perharga") {
                $total = $services->price;
            } else {
                $total = $services->price * $hours;
            }
            $bookingpaket = Bookingpaket::create([
                'kode' => self::nomat(Auth()->user()->name),
                'services_id' => $request->services_id,
                'time_from' =>  $request->time_from,
                'time_to' =>  $request->time_to,
                'user_id' => auth()->id(),
                'grand_total' => $total,
                'status' => !isset($request->status) ? 0 : $request->status
            ]);
            $notifikasiText = 'Menambahkan Data Booking Paket dengan kode: ' . $bookingpaket->kode;
            $notifikasi = Notifikasi::create([
                'user_id' => auth()->id(),
                'to_user' => $bookingpaket->user_id,
                'text' => $notifikasiText
            ]);
            return redirect()->route('admin.bookingpaket.index')->with([
                'message' => 'successfully created !',
                'alert-type' => 'success'
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Bookingpaket $bookingpaket)
    {
        abort_if(Gate::denies('bookingpaket_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.bookingpaket.show', compact('bookingpaket'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Bookingpaket $bookingpaket)
    {
        abort_if(Gate::denies('bookingpaket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $services = Services::where('status', 1)->get();


        return view('admin.bookingpaket.edit', compact('bookingpaket', 'services'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // abort_if(Gate::denies('bookingpaket_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        // $services = Services::findOrFail($request->services_id);

        // $bookingpaket->update($request->validated() + [
        //     'user_id' => auth()->id(),
        //     'grand_total'=> $services->price,
        //     'status' => !isset($request->status) ? 0 : $request->status
        // ]);
        // $services = Services::findOrFail($request->services_id);


        // $orderDate = date('Y-m-d H:i:s');
        // $paymentDue = (new \DateTime($orderDate))->modify('+1 hour')->format('Y-m-d H:i:s');

        // $bookingpaket = Bookingpaket::create($request->validated() + [
        //     'user_id' => auth()->id(),
        //     'grand_total' => $services->price,
        //     'status' => !isset($request->status) ? 0 : $request->status
        // ]);
        $statusCodes = [
            0 => 'Menunggu konfirmasi',
            1 => 'Booked',
            2 => 'Sukses',
            3 => 'Batal'
        ];
        
        $daftar = [
            'status' => 'required',
            'grand_total' => 'required'
        ];
        $validasi = $request->validate($daftar);
        
        $status = $statusCodes[$validasi['status']]; // Mengambil teks status berdasarkan kode status
        
        $bookingpaket = Bookingpaket::find($id);

        $bookingpaket->update($validasi);

        $notifikasiText = 'Mengedit Data Booking Paket - Kode: ' . $bookingpaket->kode . ' Dengan - Status: ' . $status;
        $notifikasi = Notifikasi::create([
            'user_id' => auth()->id(),
            'to_user' => $bookingpaket->user_id,
            'text' => $notifikasiText
        ]);
        
        return redirect()->route('admin.bookingpaket.index')->with([
            'message' => 'successfully updated !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bookingpaket $bookingpaket)
    {
        abort_if(Gate::denies('bookingpaket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $bookingpaket->delete();
        $kode = $bookingpaket->kode;
        $notifikasiText = 'Menghapus Data Booking Paket - Kode: ' . $kode;

        $notifikasi = Notifikasi::create([
            'user_id' => auth()->id(),
            'to_user' => $bookingpaket->user_id,
            'text' => $notifikasiText
        ]);
        return redirect()->route('admin.bookingpaket.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Delete all selected Permission at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('bookingpaket_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Bookingpaket::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
    public function laporanSearch(Request $request)
    {
        $title = Auth()->user()->name;
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');

        $bookingpaket = Bookingpaket::where('time_from', '>=', $fromDate)
            ->where('time_to', '<=', $toDate)
            ->get();
        // $totalharga = booking::where('status', 2)->sum('grand_total');
        // $jumlah = booking::where('status', 2)->count();

        return view('admin.bookingpaket.index', compact('bookingpaket'));
    }
    public function NotaPemesanan($id)
    {
        $bookingpaket = BookingPaket::find($id);
        return view('admin.bookingpaket.nota-pemesanan',compact('bookingpaket'));
    }
}
