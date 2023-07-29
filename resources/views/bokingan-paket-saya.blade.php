@extends('layouts.user')
@section('content')
<div class="container my-4" style="height: 80vh">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Bokingan Paket Saya
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Services</th>
                                <th>Jenis Paket</th>
                                <th>Jam Mulai</th>
                                <th>Jam Berakhir</th>
                                <th>Harga</th>
                                <th>Denda</th>
                                <th>Total</th>
                                <th>Bukti Bayar</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            @forelse($bookingpaket as $key => $booking)
                            <tr>                            
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $booking->kode }}</td>
                                <td>{{ $booking->services->name }}</td>
                                <td>{{ $booking->services->jenis_paket }}</td>
                                <td>{{ Carbon\Carbon::parse($booking->time_from)->format('M, d D H:i:s') }}
                                </td>
                                <td>{{ Carbon\Carbon::parse($booking->time_to)->format('M, d D H:i:s') }}
                                </td>
                                {{-- @php
                                $hour = date('h', strtotime(Carbon\Carbon::parse($booking->time_to)->format('H:i:s'))) -
                                date('h', strtotime(Carbon\Carbon::parse($booking->time_from)->format('H:i:s')));
                                @endphp
                                <td>{{ $hour }} Jam</td> --}}
                                <td>Rp{{ number_format($booking->services->price ,2, ',', '.') }}</td>                             
                                @php
                                $startDateTime = new DateTime($booking->time_from);
                                $endDateTime = new DateTime($booking->time_to);
                                $currentDateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));



                                $interval = $startDateTime->diff($endDateTime);
                                $hours = $interval->h;
                                $minutes = $interval->i;


                                $isStarted = $currentDateTime > $startDateTime;
                                $diff=$endDateTime->diff($currentDateTime);

                                // Menentukan apakah waktu saat ini melebihi waktu selesai
                                $isElapsed = $currentDateTime > $endDateTime;

                                // Menghitung total menit yang telah berlalu jika waktu telah melewati waktu selesai
                                $totalMinutes = ($isElapsed) ? ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i
                                : 0;

                                // Mendapatkan jumlah jam dan menit yang telah berlalu
                                $elapsedHours = floor($totalMinutes / 60) -1;
                                $elapsedMinutes = $totalMinutes % 60;
                                @endphp
                                  <td>
                                    @php
    
                                    @endphp
                                    {{-- Kode Blade untuk menampilkan jumlah jam --}}
                                    Durasi: {{ $hours }} Jam @if ($minutes > 0){{ $minutes }} Menit @endif
                                    <br>
                                    {{-- Kode Blade untuk menampilkan jam dan menit yang telah berlalu sejak waktu selesai --}}
                                    @if ($isElapsed && ($elapsedHours > 0))
                                    @if ($booking->status == 'Sukses')
                                    <p class="text-success">Status Telah Sukses</p>
                                    @else
                                    <p class="text-danger">
                                        Waktu telah berlalu sejak selesai:
                                        @if ($elapsedHours > 0)
                                        {{ $elapsedHours }} jam
                                        @if ($elapsedMinutes > 0)
                                        {{ $elapsedMinutes }} menit
                                        @endif
                                        Denda:
                                        Rp{{ number_format($booking->services->denda * $elapsedHours,2,',','.')  }}
                                        @endif
                                    </p>
                                    @endif
                                    @endif
                                </td>
                                @if ($booking->services->jenis_paket == 'Paket Perharga')
                                @if ($isElapsed && $elapsedHours > 0)
                                    @if ($booking->status == 'Sukses')
                                    <td>Rp{{ number_format($booking->grand_total, 2, ',', '.') }}</td>
                                    @else
                                    <td>Rp{{ number_format($booking->services->price + $booking->services->denda * $elapsedHours, 2, ',', '.') }}
                                    </td>
                                    @endif
                                @else
                                    <td>Rp{{ number_format($booking->grand_total, 2, ',', '.') }}</td>
                                @endif
                            @else
                                @if ($isElapsed && $elapsedHours > 0)
                                    @if ($booking->status == 'Sukses')
                                        <td>Rp{{ number_format($booking->grand_total, 2, ',', '.') }}</td>
                                    @else
                                        <td>Rp{{ number_format($booking->services->price * $hours + $booking->services->denda * $elapsedHours, 2, ',', '.') }}
                                        </td>
                                    @endif
                                @else
                                    @if ($booking->status == 'Sukses')
                                        <td>Rp{{ number_format($booking->grand_total, 2, ',', '.') }}</td>
                                    @else
                                        <td>Rp{{ number_format($booking->services->price * $hours + $booking->services->denda * $elapsedHours, 2, ',', '.') }}
                                        </td>
                                    @endif
                                @endif
                            @endif
                                <td><button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#exampleModalCenter{{ $key + 1 }}">
                                        Lihat
                                    </button>
                                    <div class="modal fade" id="exampleModalCenter{{ $key + 1 }}" tabindex="-1"
                                        role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
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
                                                        src="{{ asset('storage/'.$booking->bukti_bayar) }}" alt="">
                                                    @else
                                                    <p class="text-danger">
                                                        Belum ada bukti baya, silahkan bayar lalu kirim buktinya
                                                        disini
                                                    </p>
                                                    <form
                                                        action="{{ route('bookingpakets.uploadBukti', $booking->id) }}"
                                                        method="post" enctype="multipart/form-data">
                                                        @method('put')
                                                        @csrf
                                                        <div class="mb-3">
                                                            <input type="file" class="form-control" name="bukti_bayar">
                                                        </div>
                                                        <button
                                                            href=" https://api.whatsapp.com/send?phone=6281234567090&text=Nama,nomer lapangan berikut bukti pembayaran"
                                                            class="btn btn-success btn-block">{{ __('Kirim bukti perbayaran') }}</button>
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
                                        <button type="button" style="width: 70px" class="btn btn-success"
                                            data-toggle="modal" data-target="#exampleModal{{$booking->id}}">
                                            <i class="fa fa-pencil-alt" style="margin-right: 5px"></i>Edit
                                        </button>
                                        <form onclick="return confirm('Yakin Ingin Membatalkan Data Ini? ')"
                                            class="d-inline"
                                            action="{{ route('booking-paket.updateStatus', ['id' => $booking->id]) }}"
                                            method="POST">
                                            @csrf
                                            <button class="btn btn-danger" style="width: 90px">
                                                <i class="fa fa-trash" style="margin-right: 5px"></i>Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <div class="modal fade" id="exampleModal{{$booking->id}}" tabindex="-1" role="dialog"
                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Data Bokingan saya</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form action="{{ url('booking-paket/'.$booking->id.'/edit') }}"
                                                method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <div class="form-group">
                                                    <label for="time_from">{{ __('Jam Mulai') }}</label>
                                                    <input type="text" class="form-control datetimepicker"
                                                        id="time_from" name="time_from"
                                                        value="{{ old('time_from', $booking->time_from) }}" />
                                                </div>
                                                <div class="form-group">
                                                    <label for="time_to">{{ __('Jam Selesai') }}</label>
                                                    <input type="text" class="form-control datetimepicker" id="time_to"
                                                        name="time_to"
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
                                <td colspan="9" class="text-center">{{ __('Data Empty') }}</td>
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
@endsection
@push('script')
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
</script>
<script>
$('.datetimepicker').datetimepicker({
    format: 'YYYY-MM-DD HH:00',
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