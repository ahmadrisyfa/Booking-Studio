@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->


        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Content Row -->
        <div class="card shadow">
            <div class="card-header">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">{{ __('edit booking') }}</h1>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
                </div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.bookings.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('put') @php
                        $startDateTime = new DateTime($booking->time_from);
                        $endDateTime = new DateTime($booking->time_to);
                        $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
                        
                        $interval = $startDateTime->diff($endDateTime);
                        $hours = $interval->h;
                        $minutes = $interval->i;
                        
                        $isStarted = $currentDateTime > $startDateTime;
                        $diff = $endDateTime->diff($currentDateTime);
                        
                        // Menentukan apakah waktu saat ini melebihi waktu selesai
                        $isElapsed = $currentDateTime > $endDateTime;
                        
                        // Menghitung total menit yang telah berlalu jika waktu telah melewati waktu selesai
                        $totalMinutes = $isElapsed ? $diff->days * 24 * 60 + $diff->h * 60 + $diff->i : 0;
                        
                        // Mendapatkan jumlah jam dan menit yang telah berlalu
                        $elapsedHours = floor($totalMinutes / 60) - 1;
                        $elapsedMinutes = $totalMinutes % 60;
                        $total = $booking->studios->price + $booking->studios->denda * $elapsedHours;
                    @endphp
                    @if ($booking->status == 'Sukses')
                        <input type="hidden" name="grand_total" value="{{ $booking->grand_total }}">
                    @elseif ($booking->status == 'Batal')
                        <input type="hidden" name="grand_total" value="{{ $booking->grand_total }}">

                    @else
                        <input type="hidden" name="grand_total" value="{{ $total }}">
                    @endif
                    <div class="form-group">
                        <label for="studios_id">{{ __('Nomer Studio') }}</label>
                        <select name="studios_id" readonly id="studios_id" class="form-control">
                            @foreach ($studios as $studios)
                                <option {{ $booking->studios->names == $studios->string ? 'selected' : null }}
                                    value="{{ $studios->id }}">{{ $studios->names }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="price">{{ __('Jumlah Orang') }}</label>
                        <input type="number" readonly class="form-control" required id="jml_org"
                            placeholder="{{ __('jumlah org') }}"
                            name="jml_org"value="{{ old('jml_org', $booking->jml_org) }}" />
                    </div>
                    <div class="form-group">
                        <label for="time_from">{{ __('Jam Mulai') }}</label>
                        <input readonly type="text" class="form-control datetimepicker" id="time_from" name="time_from"
                            value="{{ old('time_from', $booking->time_from) }}" />
                    </div>
                    <div class="form-group">
                        <label for="time_to">{{ __('Jam Selesai') }}</label>
                        <input readonly type="text" class="form-control datetimepicker" id="time_to" name="time_to"
                            value="{{ old('time_to', $booking->time_to) }}" />
                    </div>
                    <div class="form-group">
                        <label for="time_to">{{ __('Bukti Bayar') }}</label>
                        <button type="button" class="btn btn-primary d-block" data-toggle="modal"
                            data-target="#exampleModalCenter">
                            Lihat
                        </button>
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
                            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Bukti Bayar
                                        </h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        @isset($booking->bukti_bayar)
                                            <img class="w-100" src="{{ asset('storage/' . $booking->bukti_bayar) }}"
                                                alt="">
                                        @else
                                            <p class="text-danger">
                                                Belum ada bukti baya
                                            </p>
                                        @endisset
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="status">{{ __('Status') }}</label>
                        <select name="status" id="status" class="form-control">
                            <option {{ $booking->status == 'On Proses' ? 'selected' : null }} value="0">Menunggu
                                konfirmasi</option>
                            <option {{ $booking->status == 'On Proses' ? 'selected' : null }} value="1">Booked
                            </option>
                            <option {{ $booking->status == 'Sukses' ? 'selected' : null }} value="2">Sukses</option>
                            <option {{ $booking->status == 'Batal' ? 'selected' : null }} value="3">Batal</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">{{ __('Save') }}</button>
                </form>
            </div>
        </div>


        <!-- Content Row -->

    </div>
@endsection


@push('style-alt')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endpush

@push('script-alt')
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
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
@endpush
