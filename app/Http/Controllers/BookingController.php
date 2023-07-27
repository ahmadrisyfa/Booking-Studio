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

    public function booking(Request $request)
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

        return view('booking', compact('studios', 'studiosString', 'services', 'servicesString', 'bookings', 'bookingspakets', 'events1'));
    }

    public function store(BookingRequest $request)
    {
        $studios = Studios::findOrFail($request->studios_id);


        $orderDate = date('Y-m-d H:i:s');
        $paymentDue = (new \DateTime($orderDate))->modify('+10 minute')->format('Y-m-d H:i:s');
        $startTime = $request->time_from; // Jam mulai booking dalam format datetime
        $endTime = $request->time_to; // Jam selesai booking dalam format datetime

        $startDateTime = new DateTime($startTime);
        $endDateTime = new DateTime($endTime);

        $interval = $startDateTime->diff($endDateTime);
        $hours = $interval->h;
        $harga = $studios->price * $hours;
        $booking = Booking::create($request->validated() + [
            'kode' => self::nomat(Auth()->user()->name),
            'user_id' => auth()->id(),
            'grand_total' => $harga,
            'status' => !isset($request->status) ? 0 : $request->status
        ]);

        return redirect()->route('booking.success', [$booking, $paymentDue, $harga])->with([
            'message' => 'Terimakasih sudah booking, Silahkan upload bukti pembayaran !',
            'alert-type' => 'success'
        ]);
    }

    public function edit(Request $request, $id)
    {
        $daftar = [
            'time_from' => 'required',
            'time_to' => 'required'
        ];
        $validasi = $request->validate($daftar);

        Booking::where('id', $id)
            ->update($validasi);
        return redirect(route('booking.mine'));
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
        $data['bukti_bayar'] = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        $booking->update($data);
        return redirect(route('booking.index'));
    }

    public function mine()
    {
        $bookings = Booking::where('user_id', auth()->user()->id)->get();
        return view('bokingan-saya', compact('bookings'));
    }
}
