@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Jadwal Booking
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
                        <h1 class="h3 mb-0 text-gray-800">{{ __('create Event') }}</h1>
                    </div>
                </div>
                <div class="card-body">
                    <form action="{{ url('event/booking') }}" method="POST">
                        @csrf
                        {{-- <div class="form-group mb-2">
                            <label for="services_id">{{ __('Nomer paket') }}</label>
                            <select name="services_id" id="services_id" class="form-control">
                                @foreach ($services as $services)
                                    <option {{ $servicesString == $services->name ? 'selected' : null }}
                                        value="{{ $services->id }}">{{ $services->name }}</option>
                                @endforeach
                            </select>
                        </div>                           --}}
                        <div class="form-group mb-2">
                            <label for="time_from">{{ __('Jam Mulai') }}</label>
                            <input type="text" class="form-control datetimepicker" required id="time_from" name="time_from"
                                value="{{ old('time_from') }}" />
                        </div>
                        <div class="form-group mb-2">
                            <label for="time_to">{{ __('Jam Berakhir') }}</label>
                            <input type="text" class="form-control datetimepicker" required id="time_to" name="time_to"
                                value="{{ old('time_to') }}" />
                        </div>
                        <div class="form-group mb-2">
                            <label for="catatan">{{ __('Catatan event') }}</label>
                            <textarea type="text" class="form-control" required id="catatan" name="catatan"
                                value="{{ old('catatan') }}" ></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">{{ __('Submit') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
@push('style-alt')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endpush

@push('script-alt')
{{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
</script> --}}
{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"
integrity="sha384-fQybjgWLrvvRgtW6bFlB7jaZrFsaBXjsOMm/tB9LTS58ONXgqbR9W8oWht/amnpF" crossorigin="anonymous">
</script> --}}
<script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
{{-- <script src="https://kit.fontawesome.com/3f4aa1c6f5.js" crossorigin="anonymous"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script>
    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
        minDate: moment().hour(9).startOf('hour'),
        maxDate: moment().add(2, 'months').hour(22).startOf('hour'),
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
