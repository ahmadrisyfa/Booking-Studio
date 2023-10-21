<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Studios;
use App\Models\Services;
use App\Models\Bookingpaket;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BookingpaketRequest;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Notifikasi;
class BookingpaketsController extends Controller
{

    public $sources = [
        [
            'model'      => Bookingpaket::class,
            'date_field' => 'time_from',
            'date_field_to' => 'time_to',
            'field'      => 'user_id',
            'name'      => 'services_id',
            'prefix'     => '',
            'suffix'     => '',
        ],
    ];
    public $sources1 = [
        [
            'model'      => Booking::class,
            'date_field' => 'time_from',
            'date_field_to' => 'time_to',
            'field'      => 'user_id',
            'names'      => 'studios_id',
            'prefix'     => '',
            'suffix'     => '',
        ],
    ];
    public $event = [
        [
            'model'      => Event::class,
            'date_field' => 'time_from',
            'date_field_to' => 'time_to',
            'field'      => 'user_id',
            'catatan'      => 'catatan',
            'prefix'     => '',
            'suffix'     => '',
        ],
    ];
    function nomat()
    {
        $user = Auth()->user()->name;
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
    public function index(Request $request)
    {

        $bookingpaket = [];


        foreach ($this->sources as $source) {
            $models = $source['model']::where('status', '0')
                ->get();
            foreach ($models as $model) {
                $crudFieldValue = $model->getOriginal($source['date_field']);
                $crudFieldValueTo = $model->getOriginal($source['date_field_to']);
                $service = Services::findOrFail($model->getOriginal($source['name']));
                $user = User::findOrFail($model->getOriginal($source['field']));
                $timeBreak = \Carbon\Carbon::parse($crudFieldValueTo)->format('H:i');

                if (!$crudFieldValue && $crudFieldValueTo) {
                    continue;
                }

                $services[] = [
                    'title' => trim($source['prefix'] . "($services->name)" . $user->name
                        . " ") . " " . $timeBreak,
                    'start' => $crudFieldValue,
                    'end' => $crudFieldValueTo,
                ];
            }
        }
        $services = Services::where('status', 1)->get();

        return view('welcome', compact('bookingpaket', 'services'));
    }

    public function bookingpakets(Request $request,$id)
    {


        $bookingspakets = [];
        $services = [];


        foreach ($this->sources as $source) {
            $models = $source['model']::where('status', '0')
                ->get();
            foreach ($models as $model) {
                $crudFieldValue = $model->getOriginal($source['date_field']);
                $crudFieldValueTo = $model->getOriginal($source['date_field_to']);
                $studios = services::findOrFail($model->getOriginal($source['name']));
                $user = User::findOrFail($model->getOriginal($source['field']));
                $timeBreak = \Carbon\Carbon::parse($crudFieldValueTo)->format('H:i');

                if (!$crudFieldValue && $crudFieldValueTo) {
                    continue;
                }

                $bookingspakets[] = [
                    'title' => trim($source['prefix'] . "($studios->name)" . $user->name
                        . " ") . " " . $timeBreak,
                    'start' => $crudFieldValue,
                    'end' => $crudFieldValueTo,
                ];
            }
        }
        $bookings = [];
        $services = [];


        foreach ($this->sources1 as $contoh1) {
            $models = $contoh1['model']::where('status', '0')
                ->get();
            foreach ($models as $model) {
                $crudFieldValue = $model->getOriginal($contoh1['date_field']);
                $crudFieldValueTo = $model->getOriginal($contoh1['date_field_to']);
                $studios = Studios::findOrFail($model->getOriginal($contoh1['names']));
                $user = User::findOrFail($model->getOriginal($contoh1['field']));
                $timeBreak = \Carbon\Carbon::parse($crudFieldValueTo)->format('H:i');

                if (!$crudFieldValue && $crudFieldValueTo) {
                    continue;
                }

                $bookings[] = [
                    'title' => trim($contoh1['prefix'] . "($studios->names)" . $user->name
                        . " ") . " " . $timeBreak,
                    'start' => $crudFieldValue,
                    'end' => $crudFieldValueTo,
                ];
            }
        }

        $events1 = [];
        $services = [];


        foreach ($this->event as $contoh1) {
            $models = $contoh1['model']::get();
            foreach ($models as $model) {
                $crudFieldValue = $model->getOriginal($contoh1['date_field']);
                $crudFieldValueTo = $model->getOriginal($contoh1['date_field_to']);
                $catatan = $model->getOriginal($contoh1['catatan']);

                // $studios = Studios::findOrFail($model->getOriginal($contoh1['names']));
                $user = User::findOrFail($model->getOriginal($contoh1['field']));
                $timeBreak = \Carbon\Carbon::parse($crudFieldValueTo)->format('H:i');

                if (!$crudFieldValue && $crudFieldValueTo) {
                    continue;
                }

                $events1[] = [
                    'title' => trim($contoh1['prefix'] . 'EVENT ' . $user->name
                        . " ") . " " . $timeBreak . " " . $catatan,
                    'start' => $crudFieldValue,
                    'end' => $crudFieldValueTo,
                ];
            }
        }
        $studios = Studios::where('status', 1)->get();
        $studiosString = $request->get('name');
        $services = Services::where('status', 1)->get();
        $servicesString = $request->get('name');
        $data = Services::find($id);
        return view('bookingpakets', compact('data','studios', 'studiosString', 'services', 'servicesString', 'bookingspakets', 'bookings', 'events1'));

        // $services = Services::where('status', 1)->get();
        // $servicesString = $request->get('name');

        // return view('bookingpakets', compact('services','servicesString'));
    }

    public function store(Request $request)
    {
        $services = Services::findOrFail($request->services_id);


        $orderDate = date('Y-m-d H:i:s');
        $paymentDue = (new \DateTime($orderDate))->modify('+1 hour')->format('Y-m-d H:i:s');

        $startTime = $request->time_from; // Jam mulai booking dalam format datetime
        $endTime = $request->time_to;
        // $id_services = $request->services_id;
        // dd($id_services);
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
                'kode' => self::nomat(),
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
            // $harga = $services->price * $hours;
            return redirect()->route('bookingpakets.success', [$bookingpaket, $paymentDue, $total])->with([
                'message' => 'Terimakasih sudah booking, Silahkan upload bukti pembayaran !',
                'alert-type' => 'success',
            ]);
        }
    }

    public function success($bookingpakets, $paymentDue, $harga)
    {
        return view('success_paket', compact('bookingpakets', 'paymentDue', 'harga'));
    }
    public function updateStatus(Request $request, $id)
    {
        $booking = Bookingpaket::find($id);

        $booking->status = 3;
        $booking->save();

        return redirect()->route('booking-paket.mine')->with('success', 'Status berhasil diperbarui.');
    }

    function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required',
        ]);
        
        $bookingPaket = Bookingpaket::find($id);
        $kode = $bookingPaket->kode; 
        
        if ($bookingPaket->bukti_bayar) {
            $notifikasiText = 'Mengedit Foto Pembayaran Booking Paket - Dengan Kode: ' . $kode;
        } else {
            $notifikasiText = 'Menambahkan Foto Pembayaran Booking Paket - Dengan Kode: ' . $kode;
        }
        
        
        $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        $bookingPaket->update($data);
        
        $notifikasi = Notifikasi::create([
            'user_id' => auth()->id(),
            'to_user' => $bookingPaket->user_id,
            'text' => $notifikasiText
        ]);
        
        return redirect('bookingan-paket-saya')->with('success', 'Data berhasil Di Tambahkan.');
    }
    function uploadBuktiadmin(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required',
        ]);
        
        $bookingPaket = Bookingpaket::find($id);
        $kode = $bookingPaket->kode; 
        
        if ($bookingPaket->bukti_bayar) {
            $notifikasiText = 'Mengedit Foto Pembayaran Booking Paket - Dengan Kode: ' . $kode;
        } else {
            $notifikasiText = 'Menambahkan Foto Pembayaran Booking Paket - Dengan Kode: ' . $kode;
        }
        
        
        $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        $bookingPaket->update($data);
        
        $notifikasi = Notifikasi::create([
            'user_id' => auth()->id(),
            'to_user' => $bookingPaket->user_id,
            'text' => $notifikasiText
        ]);
        
        return redirect()->back()->with('success', 'Data berhasil Di Tambahkan.');
    }
    public function mine()
    {
        $bookingpaket = Bookingpaket::where('user_id', auth()->user()->id)->get();
        return view('bokingan-paket-saya', compact('bookingpaket'));
    }
    public function edit(Request $request, $id)
    {
        $services = Services::findOrFail($request->services_id);


        $orderDate = date('Y-m-d H:i:s');
        $paymentDue = (new \DateTime($orderDate))->modify('+1 hour')->format('Y-m-d H:i:s');

        $startTime = $request->time_from; // Jam mulai booking dalam format datetime
        $endTime = $request->time_to;
        // $id_services = $request->services_id;
        // dd($id_services);
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
            $bookingpaket = Bookingpaket::find($id);
            $bookingpaket->update([
                'services_id' => $request->services_id,
                'time_from' =>  $request->time_from,
                'time_to' =>  $request->time_to,
                'user_id' => auth()->id(),
                'grand_total' => $total,
            ]);
            $notifikasiText = 'Menambahkan Data Booking Paket dengan kode: ' . $bookingpaket->kode;

            $notifikasi = Notifikasi::create([
                'user_id' => auth()->id(),
                'to_user' => $bookingpaket->user_id,
                'text' => $notifikasiText
            ]);
            return redirect()->back();
        }
    }
    public function NotaPemesanan($id)
    {
        $bookingpaket = Bookingpaket::find($id);
        return view('nota_pemesanan_bokingan_paket_saya',compact('bookingpaket'));
    }
}
