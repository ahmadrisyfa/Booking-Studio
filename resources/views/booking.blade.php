@extends('layouts.user')
@section('content')
<div class="container my-5">

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (session()->has('message'))
    <div class="alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show" role="alert" id="alert-message">
        {{ session()->get('message') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Jadwal Studio
                </div>

                <div class="card-body">
                    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />

                    <div id='calendar'></div>
                </div>
            </div><br>
        </div>
        <div class="col-lg-12">
            <div class="card shadow">
                <div class="card-header">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ __('create booking') }}</h1>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ route('booking.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="studios_id">{{ __('Nomer studio') }}</label>
                            <select name="studios_id" id="studios_id" class="form-control">
                                @foreach ($studios as $studios)
                                <option {{ $studiosString == $studios->names ? 'selected' : null }} value="{{ $studios->id }}">{{ $studios->names }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-2">
                            <label for="price">{{ __('Jumlah Orang') }}</label>
                            <input type="number" class="form-control" id="jml_org" placeholder="{{ __('jumlah org') }}" name="jml_org" value="{{ old('org') }}" />
                        </div>
                        <div class="form-group mb-2">
                            <label for="time_from">{{ __('Jam Mulai') }}</label>
                            <input type="text" class="form-control datetimepicker" id="time_from" name="time_from" value="{{ old('time_from') }}" />
                        </div>
                        <div class="form-group mb-2">
                            <label for="time_to">{{ __('Jam Berakhir') }}</label>
                            <input type="text" class="form-control datetimepicker" id="time_to" name="time_to" value="{{ old('time_to') }}" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Booking') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@push('script')
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
{{-- <script src="https://kit.fontawesome.com/3f4aa1c6f5.js" crossorigin="anonymous"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script>
 $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            minDate: moment().hour(9).startOf('hour'),
            maxDate: moment().hour(22).startOf('hour').add(9999999999999, 'day'),
            locale: 'en',
            sideBySide: true,
            icons: {
                up: 'fas fa-chevron-up',
                down: 'fas fa-chevron-down',
                previous: 'fas fa-chevron-left',
                next: 'fas fa-chevron-right'
            },
            stepping: 10
        });
        $('form').submit(function(event) {
        var time_from = moment($('#time_from').val(), 'YYYY-MM-DD HH:mm');
        var time_to = moment($('#time_to').val(), 'YYYY-MM-DD HH:mm');

        if (time_to.isBefore(time_from)) {
            alert('Jam Akhir tidak boleh kurang dari Dari Jam Mulai.');
            event.preventDefault();
        } else {
          
        }
             });
</script>
<script>
    $(document).ready(function() {

        bookingspakets = {!! json_encode($bookingspakets) !!};
        bookings = {!! json_encode($bookings) !!};
        events1 = {!! json_encode($events1) !!};


        console.log(bookingspakets,bookings,events1)
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: { center: 'dayGridMonth,timeGridWeek' }, // buttons for switching between views

views: {
dayGridMonth: { // name of view
  titleFormat: { year: 'numeric', month: '2-digit', day: '2-digit' }
  // other view-specific options here
}
},
                events: bookings.concat(events1,bookingspakets),
        initialView: 'dayGridMonth'
        });
        calendar.render();
        // $('#calendar').fullCalendar({
        //     events: bookings.concat(events1,bookingspakets),
        // });
    });
</script>
@endpush