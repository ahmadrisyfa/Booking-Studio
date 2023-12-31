<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingPaket;
use App\Models\Booking;
use App\Models\Services;
use App\Models\Event;
use App\Models\User;
use App\Models\Studios;
use App\Http\Requests\Admin\EventRequest;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Notifikasi;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
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
    // public function index(){
    //     return view('admin.dashboard');
    // }
    public function index(Request $request){

        
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
            $models = $contoh1['model']::
                get();
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
                        . " ") . " " . $timeBreak . " ". $catatan,
                    'start' => $crudFieldValue,
                    'end' => $crudFieldValueTo,
                ];
            }
        }

        $studios = Studios::where('status', 1)->get();
        $studiosString = $request->get('name');
        $services = Services::where('status', 1)->get();
        $servicesString = $request->get('name');

        return view('admin.dashboard', compact('studios', 'studiosString', 'services', 'servicesString', 'bookingspakets','bookings','events1'));

        // $services = Services::where('status', 1)->get();
        // $servicesString = $request->get('name');

        // return view('bookingpakets', compact('services','servicesString'));
    }
    public function store(Request $request)
    {
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
          $event = Event::create([
            'user_id' => auth()->id(),
            'catatan' => $request->catatan,
            'time_to' => $request->time_to,
            'time_from' => $request->time_from,
        ]);

        $notifikasiText = 'Menambahkan Data Event Dendan Catatan: ' . $event->catatan;

        $notifikasi = Notifikasi::create([
            'user_id' => auth()->id(),
            'to_user' => $event->user_id,
            'text' => $notifikasiText
        ]);
        return redirect('admin/dashboard')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);
        }
    }
}
