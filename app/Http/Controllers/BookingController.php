<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Studios;
use App\Models\Services;
use App\Models\Booking;
use App\Models\BookingPaket;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\BookingRequest;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
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
    function nomat($bkg)
    {

        $tgl = date('y-m-d');
        $number = Booking::where('created_at', 'like', '%' . $tgl . '%')->count();
        $angka = $number + 1;
        $codes = str_pad($angka, 1, rand(11, 99), STR_PAD_LEFT);
        // $code = 'SIM-' . date('ymd') . $codes . Str::random(15);$string = "John Doe";
        $words = explode(' ', $bkg);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper($word[0]);
        }
        $code = 'BKG' . date('Ymd') . $initials . Str::random(2);
        return $code;
    }
    public function index(Request $request)
    {

        // $bookings = [];
        // $services = [];


        // foreach ($this->sources as $source) {
        //     $models = $source['model']::where('status', '0')
        //         ->get();
        //     foreach ($models as $model) {
        //         $crudFieldValue = $model->getOriginal($source['date_field']);
        //         $crudFieldValueTo = $model->getOriginal($source['date_field_to']);
        //         $studios = Studios::findOrFail($model->getOriginal($source['names']));
        //         $user = User::findOrFail($model->getOriginal($source['field']));
        //         $timeBreak = \Carbon\Carbon::parse($crudFieldValueTo)->format('H:i');

        //         if (!$crudFieldValue && $crudFieldValueTo) {
        //             continue;
        //         }

        //         $bookings[] = [
        //             'title' => trim($source['prefix'] . "($studios->names)" . $user->name
        //                 . " ") . " " . $timeBreak,
        //             'start' => $crudFieldValue,
        //             'end' => $crudFieldValueTo,
        //         ];
        //     }
        // }
        $studios = Studios::where('status', 1)->get();
        $services = Services::where('status', 1)->get();

        return view('welcome', compact('studios',  'services'));
    }

    public function booking(Request $request,$id)
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
                    'title' => trim($source['prefix']  . "($studios->name)" . $user->name
                        . " ") . " " . $timeBreak,
                    'start' => $crudFieldValue,
                    'end' => $crudFieldValueTo,
                ];
            }
        }
        $bookings = [];
        $services = [];


        foreach ($this->sources1 as $source1) {
            $models = $source1['model']::where('status', '0')
                ->get();
            foreach ($models as $model) {
                $crudFieldValue = $model->getOriginal($source1['date_field']);
                $crudFieldValueTo = $model->getOriginal($source1['date_field_to']);
                $studios = Studios::findOrFail($model->getOriginal($source1['names']));
                $user = User::findOrFail($model->getOriginal($source1['field']));
                $timeBreak = \Carbon\Carbon::parse($crudFieldValueTo)->format('H:i');

                if (!$crudFieldValue && $crudFieldValueTo) {
                    continue;
                }

                $bookings[] = [
                    'title' => trim($source1['prefix'] . "($studios->names)" . $user->name
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
        $studiosString = $request->get('names');
        $services = Services::where('status', 1)->get();
        $servicesString = $request->get('name');
        $data = Studios::find($id);
        return view('booking', compact('data','studios', 'studiosString', 'services', 'servicesString', 'bookings', 'bookingspakets', 'events1'));
    }

    public function store(Request $request)
    {
        $studios = Studios::findOrFail($request->studios_id);
        $orderDate = date('Y-m-d H:i:s');
        $paymentDue = (new \DateTime($orderDate))->modify('+10 minute')->format('Y-m-d H:i:s');
        // $studios->command('Y-m-d H:i:s')->withoutOverlapping();
        // dd($startTime);
        $startTime = $request->time_from; // Jam mulai booking dalam format datetime
        $endTime = $request->time_to;

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

        $startDateTime = new DateTime($startTime);
        $endDateTime = new DateTime($endTime);

        $interval = $startDateTime->diff($endDateTime);
        $hours = $interval->h;
        $jml_org = $request->jml_org;
        $harga = $studios->price * $jml_org * $hours;
        $booking = Booking::create([
            'kode' => self::nomat(Auth()->user()->name),
            'user_id' => auth()->id(),
            'jml_org' => $request->jml_org,
            'studios_id' => $request->studios_id,
            'time_to' => $request->time_to,
            'time_from' => $request->time_from,
            'grand_total' => $harga,
            'status' => !isset($request->status) ? 0 : $request->status
        ]);
        $notifikasiText = 'Menambahkan Data Booking Studio dengan kode: ' . $booking->kode;

        $notifikasi = Notifikasi::create([
            'user_id' => auth()->id(),
            'to_user' => $booking->user_id,
            'text' => $notifikasiText
        ]);
        return redirect()->back();
        }
    }

    public function edit(Request $request, $id)
    {        
        $studios = Studios::findOrFail($request->studios_id);
        $orderDate = date('Y-m-d H:i:s');
        $paymentDue = (new \DateTime($orderDate))->modify('+10 minute')->format('Y-m-d H:i:s');
        $startTime = $request->time_from; 
        $endTime = $request->time_to;

        $bookingExists = DB::table('bookings')
        ->where(function ($query) use ($startTime, $endTime) {
            $query->where(function ($query) use ($startTime, $endTime) {
                $query->where('time_to', '>=', $startTime)
                    ->where('time_from', '<=', $endTime);
            });
        })
        ->exists();
        $bookingPaketsExists = DB::table('bookingpakets')
        ->where(function ($query) use ($startTime, $endTime) {
        $query->where(function ($query) use ($startTime, $endTime) {
            $query->where('time_to', '>=', $startTime)
                ->where('time_from', '<=', $endTime);
        });
        })
        ->exists();
        $EventExists = DB::table('event')
        ->where(function ($query) use ($startTime, $endTime) {
        $query->where(function ($query) use ($startTime, $endTime) {
            $query->where('time_to', '>=', $startTime)
                ->where('time_from', '<=', $endTime);
        });
        })
        ->exists();
        if ($bookingExists || $bookingPaketsExists || $EventExists) {
        return redirect()->back()->with([
            'message' => 'Maaf, waktu tersebut sudah dipesan oleh orang lain.',
            'alert-type' => 'danger'
        ]);
        } else {
            $startDateTime = new DateTime($startTime);
            $endDateTime = new DateTime($endTime);
            $interval = $startDateTime->diff($endDateTime);
            $hours = $interval->h;
            $jml_org = $request->jml_org;
            $harga = $studios->price * $jml_org * $hours;
            $booking = Booking::find($id);

            $booking->update([
                'user_id' => auth()->id(),
                'time_to' => $request->time_to,
                'time_from' => $request->time_from,
            ]);
            $notifikasiText = 'Menggedit Data Booking Jam Studio dengan kode: ' . $booking->kode;    
            $notifikasi = Notifikasi::create([
                'user_id' => auth()->id(),
                'to_user' => $booking->user_id,
                'text' => $notifikasiText
            ]);
            return redirect('bookingan-saya');
        }
    }
    

    public function updateStatus(Request $request, $id)
    {
        $booking = Booking::find($id);

        $booking->status = 3;
        $booking->save();

        return redirect()->route('booking.mine')->with('success', 'Status berhasil diperbarui.');
    }

    public function success($booking, $paymentDue, $harga)
    {
        return view('success', compact('paymentDue', 'booking', 'harga'));
    }

    function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required',
        ]);
        
        $booking = Booking::find($id);
        $kode = $booking->kode; 
        
        if ($booking->bukti_bayar) {
            $notifikasiText = 'Mengedit Foto Pembayaran Booking Studio - Dengan Kode: ' . $kode;
        } else {
            $notifikasiText = 'Menambahkan Foto Pembayaran Booking Studio - Dengan Kode: ' . $kode;
        }
        
        $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        $booking->update($data);
        
        $notifikasi = Notifikasi::create([
            'user_id' => auth()->id(),
            'to_user' => $booking->user_id,
            'text' => $notifikasiText
        ]);
        
        return redirect('bookingan-saya')->with('success', 'Data Berhasil Di Tambahkan.');
    }
    function uploadBuktiadmin(Request $request, $id)
    {
        $request->validate([
            'bukti_bayar' => 'required',
        ]);
        
        $booking = Booking::find($id);
        $kode = $booking->kode; 
        
        if ($booking->bukti_bayar) {
            $notifikasiText = 'Mengedit Foto Pembayaran Booking Studio - Dengan Kode: ' . $kode;
        } else {
            $notifikasiText = 'Menambahkan Foto Pembayaran Booking Studio - Dengan Kode: ' . $kode;
        }
        
        $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        $booking->update($data);
        
        $notifikasi = Notifikasi::create([
            'user_id' => auth()->id(),
            'to_user' => $booking->user_id,
            'text' => $notifikasiText
        ]);
        
        return redirect()->back()->with('success', 'Data Berhasil Di Tambahkan.');
    }

    public function mine()
    {
        $bookings = Booking::where('user_id', auth()->user()->id)->get();
        return view('bokingan-saya', compact('bookings'));
    }
    public function NotaPemesanan($id)
    {
        $bookings = Booking::find($id);
        return view('nota_pemesanan_bokingan_saya',compact('bookings'));
    }
  
}
