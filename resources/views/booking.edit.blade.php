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
            <div class="alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show" role="alert"
                id="alert-message">
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
                        <link rel='stylesheet'
                            href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />

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
                                <label for="time_from">{{ __('Jam Mulai') }}</label>
                                <input type="text" class="form-control datetimepicker" id="time_from" name="time_from"
                                    value="{{ old('time_from,$booking->time_from') }}" />
                            </div>
                            <div class="form-group mb-2">
                                <label for="time_to">{{ __('Jam Berakhir') }}</label>
                                <input type="text" class="form-control datetimepicker" id="time_to" name="time_to"
                                    value="{{ old('time_to,$booking->time_to') }}" />
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">{{ __('Simpan') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
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
    </script>
    <script>
        $(document).ready(function() {
            // page is now ready, initialize the calendar...

            bookings = {!! json_encode($bookings) !!};

            console.log(bookings)
            $('#calendar').fullCalendar({
                // put your options and callbacks here
                events: bookings


            });
        });
    </script>
@endpush
