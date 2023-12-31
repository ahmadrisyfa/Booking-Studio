@extends('layouts.user')
@section('content')
    <div class="container my-4" style="height: 80vh">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        Bokingan Saya
                    </div>
                    <div class="card-body">
                        @if (session()->has('message'))
                        <div class="alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show" role="alert"
                            id="alert-message">
                            {{ session()->get('message') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif
                        <div class="table-responsive">
                            @if (session()->has('success'))
                                <div class="alert alert-{{ session()->get('alert-type') }} alert-dismissible fade show"
                                    role="alert" id="alert-message">
                                    {{ session()->get('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif
                            <table class="table table-bordered">
                                <tr>
                                    <th>No</th>
                                    <th>Studio</th>
                                    <th>kode</th>
                                    <th>Jam Mulai</th>
                                    <th>Jam Berakhir</th>
                                    {{-- <th>Total Jam</th> --}}
                                    <th>Total Penyewa</th>
                                    <th>Harga</th>
                                    <th>Denda</th>
                                    <th>Total</th>
                                    <th>Bukti Bayar</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                                @forelse($bookings as $key => $booking)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $booking->studios->names }}</td>
                                        <td>{{ $booking->kode }}</td>
                                        <td>{{ Carbon\Carbon::parse($booking->time_from)->format('M, d D H:i:s') }}
                                        </td>
                                        <td>{{ Carbon\Carbon::parse($booking->time_to)->format('M, d D H:i:s') }}
                                        </td>
                                        {{-- @php
                                $hour = date('h', strtotime(Carbon\Carbon::parse($booking->time_to)->format('H:i:s'))) -
                                date('h', strtotime(Carbon\Carbon::parse($booking->time_from)->format('H:i:s')));
                                @endphp
                                <td>{{ $hour }} Jam</td> --}}
                                        <td>{{ number_format($booking->jml_org) }} Orang</td>
                                        @php
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

                                        <td>Rp{{ number_format($booking->studios->price * $booking->jml_org * $hours, 2, ',', '.') }}
                                        </td>
                                        <td>

                                            {{-- Kode Blade untuk menampilkan jumlah jam --}}
                                            Durasi: {{ $hours }} Jam @if ($minutes > 0)
                                                {{ $minutes }} Menit
                                            @endif
                                            <br>
                                            {{-- @if ($startDateTime->format('Y-m-d') == $currentDateTime->format('Y-m-d')) --}}
                                            {{-- Kode Blade untuk menampilkan jam dan menit yang telah berlalu sejak waktu selesai --}}
                                            @if ($isElapsed && $elapsedHours > 0)
                                                @if ($booking->status == 'Sukses')
                                                    <p class="text-success">Status Telah Sukses</p>
                                                @elseif ($booking->status == 'Batal')
                                                    <p class="text-success">Status Telah Di Batalkan</p>
                                                @else
                                                    <p class="text-danger">
                                                        Waktu telah berlalu sejak selesai:
                                                        @if ($elapsedHours > 0)
                                                            {{ $elapsedHours }} jam
                                                            @if ($elapsedMinutes > 0)
                                                                {{ $elapsedMinutes }} menit
                                                            @endif
                                                            Denda:
                                                            Rp{{ number_format($booking->studios->denda * $elapsedHours, 2, ',', '.') }}
                                                        @endif
                                                    </p>
                                                @endif
                                            @endif
                                            {{-- @endif --}}
                                        </td>
                                        @if ($isElapsed && $elapsedHours > 0)
                                            @if ($booking->status == 'Sukses')
                                                <td>Rp{{ number_format($booking->grand_total, 2, ',', '.') }}</td>
                                            @elseif ($booking->status == 'Batal')
                                                <td>Rp{{ number_format($booking->grand_total, 2, ',', '.') }}</td>
                                            @else
                                                <td>Rp{{ number_format($booking->studios->price * $booking->jml_org * $hours + $booking->studios->denda * $elapsedHours, 2, ',', '.') }}
                                                </td>
                                            @endif
                                        @else
                                            <td>Rp{{ number_format($booking->studios->price * $booking->jml_org * $hours, 2, ',', '.') }}
                                            </td>
                                        @endif
                                        <td><button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#exampleModalCenter{{ $key + 1 }}">
                                                Lihat</button>
                                            <div class="modal fade" id="exampleModalCenter{{ $key + 1 }}"
                                                tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalCenterTitle">Bukti Bayar
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            @isset($booking->bukti_bayar)
                                                                <img class="w-100"
                                                                    src="{{ asset('storage/' . $booking->bukti_bayar) }}"
                                                                    alt="">
                                                                <h5 style="text-align: center;margin:10px">Edit bukti Pembayaran
                                                                </h5>
                                                                <form action="{{ route('booking.uploadBukti', $booking->id) }}"
                                                                    enctype="multipart/form-data" method="POST">
                                                                    @method('put')
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <input name="bukti_bayar" type="file"
                                                                            class="form-control">
                                                                    </div>
                                                                    <button class="btn btn-success btn-block">kirim</button>
                                                                </form>
                                                            @else
                                                                <p class="text-danger">
                                                                    Belum ada bukti baya, silahkan bayar lalu kirim buktinya
                                                                    disini
                                                                </p>
                                                                <form action="{{ route('booking.uploadBukti', $booking->id) }}"
                                                                    enctype="multipart/form-data" method="POST">
                                                                    @method('put')
                                                                    @csrf
                                                                    <div class="mb-3">
                                                                        <input name="bukti_bayar" type="file"
                                                                            class="form-control">
                                                                    </div>
                                                                    <button class="btn btn-success btn-block">kirim</button>
                                                                </form>
                                                            @endisset
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $booking->status }}</td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                @isset($booking->bukti_bayar)
                                                    <span style="width: 70px" class="btn btn-info">
                                                        <i class="fa fa-pencil-alt" style="margin-right: 5px"></i>Tidak Bisa
                                                        Edit Jam
                                                    </span>
                                                @else
                                                    <button type="button" style="width: 70px" class="btn btn-success"
                                                        data-toggle="modal" data-target="#exampleModal{{ $booking->id }}">
                                                        <i class="fa fa-pencil-alt" style="margin-right: 5px"></i>Edit
                                                    </button>
                                                @endisset

                                                <form onclick="return confirm('Yakin Ingin Membatalkan Data Ini? ')"
                                                    class="d-inline"
                                                    action="{{ route('booking.updateStatus', ['id' => $booking->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <button class="btn btn-danger" style="width: 90px">
                                                        <i class="fa fa-trash" style="margin-right: 5px"></i>Hapus
                                                    </button>
                                                </form>
                                                <a href="{{ url('bookingan-saya/nota_pemesanan/' . $booking->id) }}"
                                                    target="_blank" class="btn btn-warning">
                                                    {{-- Nota Pemesanan --}}
                                                    {{-- <i class="fa fa-print"></i> --}}
                                                    Nota Pesanan
                                                </a>
                                            </div>
                                        </td>
                                    </tr>

                                    <div class="modal fade" id="exampleModal{{ $booking->id }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLabel">Edit Data Bokingan saya
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body">
                                                    <form action="{{ url('booking/' . $booking->id . '/edit') }}"
                                                        method="POST" enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')
                                                        <div class="form-group">
                                                            <label for="time_from">{{ __('Jam Mulai') }}</label>
                                                            <input type="text" class="form-control datetimepicker"
                                                                id="time_from" name="time_from"
                                                                value="{{ old('time_from', $booking->time_from) }}" />
                                                                <input type="hidden" name="studios_id" id="studios_id" value="{{$booking->studios_id}}">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="time_to">{{ __('Jam Selesai') }}</label>
                                                            <input type="text" class="form-control datetimepicker"
                                                                id="time_to" name="time_to"
                                                                value="{{ old('time_to', $booking->time_to) }}" />
                                                        </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                                </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center">{{ __('Data Empty') }}</td>
                                    </tr>
                                @endforelse
                            </table>
                            <th>*Jika Pesanan yang sudah dibayar dibatalkan maka uang akan hangus</th>
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
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
    </script>
    <script>
        $('.datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            // enabledHours: [9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20],
            minDate: moment().hour(9).startOf('hour'),
            maxDate: moment().add(2, 'months').hour(22).startOf('hour'),
            locale: 'en',
            sideBySide: true,
            icons: {
                up: 'fa fa-chevron-up',
                down: 'fa fa-chevron-down',
                previous: 'fa fa-chevron-left',
                next: 'fa fa-chevron-right'
            },
            stepping: 10
        });

        $('form').submit(function(event) {
            var time_from = moment($('#time_from').val(), 'YYYY-MM-DD HH:mm');
            var time_to = moment($('#time_to').val(), 'YYYY-MM-DD HH:mm');

            if (time_to.isBefore(time_from)) {
                alert('Jam Akhir tidak boleh kurang dari Dari Jam Mulai.');
                event.preventDefault();
            }
        });
        $('form').submit(function(event) {
            var time_from = moment($('#time_from_modal').val(), 'YYYY-MM-DD HH:mm');
            var time_to = moment($('#time_to_modal').val(), 'YYYY-MM-DD HH:mm');

            if (time_to.isBefore(time_from)) {
                alert('Jam Akhir tidak boleh kurang dari Dari Jam Mulai.');
                event.preventDefault();
            }
        });
    </script>
@endpush
