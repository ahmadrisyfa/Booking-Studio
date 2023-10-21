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
                    <div class="row mt-2">
                        <div class="col-sm-12 mb-2">
                            <h3 style="text-align: center">Detail Studios</h3>
                        </div>
                        <div class="col-sm-6">
                            <div id="carouselExampleControls{{ $data->id }}" class="carousel slide"
                                data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach (json_decode($data->image) as $index => $imagePath)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            <div style="display: flex; justify-content: center; align-items: center;">
                                                <img src="{{ asset('storage/image-studios/' . $imagePath) }}"
                                                    class="d-block" style="width:400px" alt="Service Image">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <a class="carousel-control-prev" href="#carouselExampleControls{{ $data->id }}"
                                    role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"
                                        style="background-color: rgb(52, 52, 52)"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls{{ $data->id }}"
                                    role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"
                                        style="background-color:  rgb(52, 52, 52)"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <p>Nama: {{ $data->names }}</p>
                            <p>Harga Perjam: {{ $data->price }}</p>
                            <p>Denda Perjam: {{ $data->denda }}</p>
                            <p>Deskripsi: {!! $data->deskripsi !!}</p>
                        </div>
                    </div>
                    <div class="modal-body">

                        <form action="{{ route('booking.store') }}" method="POST">
                            @csrf
                            <input type="hidden" class="form-control" id="studios_id" placeholder="{{ __('jumlah org') }}"
                                name="studios_id" value="{{ $data->id }}" />
                            <div class="form-group mb-2">
                                <label for="price">{{ __('Jumlah Orang') }}</label>
                                <input required type="number" class="form-control" id="jml_org"
                                    placeholder="{{ __('jumlah org') }}" name="jml_org" value="{{ old('org') }}" />
                            </div>
                            <div class="form-group mb-2">
                                <label for="time_from">{{ __('Jam Mulai') }}</label>
                                <input required type="text" class="form-control datetimepicker" id="time_from"
                                    name="time_from" value="{{ old('time_from') }}" />
                            </div>
                            <div class="form-group mb-2">
                                <label for="time_to">{{ __('Jam Berakhir') }}</label>
                                <input required type="text" class="form-control datetimepicker" id="time_to"
                                    name="time_to" value="{{ old('time_to') }}" />
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">{{ __('Booking') }}</button>
                        </form>
                    </div>
                    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="bookingModalLabel">Booking Paket Form</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('booking.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" class="form-control" id="studios_id"
                                            placeholder="{{ __('jumlah org') }}" name="studios_id"
                                            value="{{ $data->id }}" />
                                        <div class="form-group mb-2">
                                            <label for="price">{{ __('Jumlah Orang') }}</label>
                                            <input required type="number" class="form-control" id="jml_org"
                                                placeholder="{{ __('jumlah org') }}" name="jml_org"
                                                value="{{ old('org') }}" />
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="time_from">{{ __('Jam Mulai') }}</label>
                                            <input required type="text" class="form-control datetimepicker"
                                                id="time_from_modal" name="time_from" value="{{ old('time_from') }}" />
                                        </div>
                                        <div class="form-group mb-2">
                                            <label for="time_to">{{ __('Jam Berakhir') }}</label>
                                            <input required type="text" class="form-control datetimepicker"
                                                id="time_to_modal" name="time_to" value="{{ old('time_to') }}" />
                                        </div>
                                        <button type="submit"
                                            class="btn btn-primary btn-block">{{ __('Booking') }}</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endsection

@push('script')
    <script src='https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
    {{-- <script src="https://kit.fontawesome.com/3f4aa1c6f5.js" crossorigin="anonymous"></script> --}}
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
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
        $('form').submit(function(event) {
            var time_from = moment($('#time_from_modal').val(), 'YYYY-MM-DD HH:mm');
            var time_to = moment($('#time_to_modal').val(), 'YYYY-MM-DD HH:mm');

            if (time_to.isBefore(time_from)) {
                alert('Jam Akhir tidak boleh kurang dari Dari Jam Mulai.');
                event.preventDefault();
            } else {

            }
        });
    </script>
    <script>
        $(document).ready(function() {
            var bookingspakets = {!! json_encode($bookingspakets) !!};
            var bookings = {!! json_encode($bookings) !!};
            var events1 = {!! json_encode($events1) !!};

            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    center: 'dayGridMonth,timeGridWeek'
                },
                views: {
                    dayGridMonth: {
                        titleFormat: {
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit'
                        }
                    }
                },
                events: bookings.concat(events1, bookingspakets),
                initialView: 'dayGridMonth',
                dateClick: function(info) {
                    var selectedDate = info.date;
                    var formattedDateTime = moment(selectedDate).format('YYYY-MM-DD 09:00');
                    $("#time_from_modal").val(formattedDateTime);
                    $("#bookingModal").modal("show");
                }
            });

            calendar.render();
        });
    </script>
@endpush

{{-- kode berhasil --}}
{{-- <script>
    $(document).ready(function() {
        var bookingspakets = {!! json_encode($bookingspakets) !!};
        var bookings = {!! json_encode($bookings) !!};
        var events1 = {!! json_encode($events1) !!};

        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                center: 'dayGridMonth,timeGridWeek'
            },
            views: {
                dayGridMonth: {
                    titleFormat: {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit'
                    }
                }
            },
            events: bookings.concat(events1, bookingspakets),
            initialView: 'dayGridMonth',
            dateClick: function(info) {
                var selectedDate = info.date;
                var formattedDate = moment(selectedDate).format('YYYY-MM-DD HH:mm');
                $("#time_from").val(formattedDate);
            }
        });
        calendar.render();
    });
</script> --}}
