<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Studios;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BookingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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
    public function index()
    {
        abort_if(Gate::denies('booking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $bookings = Booking::all();

        return view('admin.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        abort_if(Gate::denies('booking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $studios = Studios::where('status', 1)->get();
        $studiosString = $request->get('names');

        return view('admin.bookings.create', compact('studios', 'studiosString'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('booking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $studios = Studios::findOrFail($request->studios_id);
        // $studios->command('Y-m-d H:i:s')->withoutOverlapping();
        // dd($startTime);
        $startTime = $request->time_from; // Jam mulai booking dalam format datetime
        $endTime = $request->time_to;

        $bookingExists = DB::table('bookings')
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($query) use ($startTime, $endTime) {
                    $query->where('time_to', '>=', $startTime)
                        ->where('time_from', '<=', $endTime);
                });
            })
            ->exists();
        if ($bookingExists) {
            // Ada booking lain pada waktu yang sama
            return redirect()->route('admin.bookings.index')->with([
                'message' => 'Maaf, waktu tersebut sudah dipesan oleh orang lain.',
                'alert-type' => 'danger'
            ]);
        } else {
            Booking::create([
                'kode' => self::nomat(Auth()->user()->name),
                'user_id' => auth()->id(),
                'jml_org' => $request->jml_org,
                'studios_id' => $request->studios_id,
                'time_to' => $request->time_to,
                'time_from' => $request->time_from,
                'grand_total' => $studios->price,
                'status' => !isset($request->status) ? 0 : $request->status
            ]);
            return redirect()->route('admin.bookings.index')->with([
                'message' => 'successfully created !',
                'alert-type' => 'success'
            ]);
        }
        // Booking::create($request->validate() + [
        //     'user_id' => auth()->id(),
        //     'grand_total' => $studios->price,
        //     'status' => !isset($request->status) ? 0 : $request->status
        // ]);


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Booking $booking)
    {
        abort_if(Gate::denies('booking_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $studios = Studios::where('status', 1)->get();

        return view('admin.bookings.edit', compact('booking', 'studios'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BookingRequest $request, Booking $booking)
    {
        abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $studios = Studios::findOrFail($request->studios_id);

        $booking->update($request->validated() + [
            'grand_total' => $request->grand_total,
            'jml_org' => $request->jml_org,
            'status' => !isset($request->status) ? 0 : $request->status
        ]);

        return redirect()->route('admin.bookings.index')->with([
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
    public function destroy(Booking $booking)
    {
        abort_if(Gate::denies('booking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $booking->delete();

        return redirect()->route('admin.bookings.index')->with([
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
        abort_if(Gate::denies('booking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        Booking::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
