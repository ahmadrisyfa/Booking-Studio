@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->


        <!-- Content Row -->
    
        <div class="card">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <form action="{{url('admin/bookingpaket/search')}}" method="post">
                        @csrf
                        <div class="col-md-3 col-sm-3 ">
                            Dari Tanggal <input id="fromDate" name="fromDate" value="{{ request('fromDate') }}"
                                class="date-picker form-control" type="date" required>
                        </div>
                        <div class="col-md-3 col-sm-3 ">
                            Sampai Tanggal
                            <input id="toDate" name="toDate" value="{{ request('toDate') }}" class="date-picker form-control"
                                type="date" required>
                        </div>
                        <div class="col-md-3" style="margin-top: 18px;">        
                            <button class="btn btn-info" style="font-weight:bold" type="submit"><i class="fa fa-search"
                                    style="margin-right:8px"></i>Cari</button>
                                    {{-- <button type="button" class="btn btn-success" onclick="cetaktable()">Cetak</button> --}}
                                    {{-- <button type="button" class="btn btn-info" onclick="download()">Download</button> --}}
                        </div>
                    </form>
                </div>
            </div>

            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('List Booking Paket') }}
                </h6>
                
                <div class="ml-auto">
                    @can('booking_create')
                        <a href="{{ route('admin.bookingpaket.create') }}" class="btn btn-primary">
                            <span class="icon text-white-50">
                                <i class="fa fa-plus"></i>
                            </span>
                            <span class="text">{{ __('Buat Booking Paket') }}</span>
                        </a>
                    @endcan
                </div>
                
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable datatable-bookingpaket"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama Penyewa</th>
                                <th>Nama service</th>
                                <th>Jenis Paket</th>
                                <th>Jam Mulai</th>
                                <th>Jam Berakhir</th>
                                <th>Harga</th>
                                <th>Denda</th>
                                <th>Total</th>
                                <th>Bukti Pembayaran</th>
                                <th>Tanggal Di Pesan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookingpaket as $key=>$bookingpaket)
                                <tr data-entry-id="{{ $bookingpaket->id }}">
                                    <td>
                                        @php
                                            $startDateTime = new DateTime($bookingpaket->time_from);
                                            $endDateTime = new DateTime($bookingpaket->time_to);
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
                                        @endphp
                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bookingpaket->kode }}</td>
                                    <td>{{ $bookingpaket->user->name }}</td>
                                    <td>{{ $bookingpaket->services->name }}</td>
                                    <td><button class="btn btn-info">{{ $bookingpaket->services->jenis_paket }}</button>
                                    </td>
                                    <td>{{ Carbon\Carbon::parse($bookingpaket->time_from)->format('M, d D H:i:s') }}</td>
                                    <td>{{ Carbon\Carbon::parse($bookingpaket->time_to)->format('M, d D H:i:s') }}</td>
                                    {{-- <td>Rp @if ($elapsedHours > 0){{ number_format($bookingpaket->services->denda * $elapsedHours + $bookingpaket->grand_total,2,',','.')  }}@else
                            {{ number_format($bookingpaket->grand_total,2,',','.')  }}@endif
                            </td> --}}
                                    <td>Rp{{ number_format($bookingpaket->services->price, 2, ',', '.') }}</td>
                                    <td>
                                        @php
                                            
                                        @endphp
                                        {{-- Kode Blade untuk menampilkan jumlah jam --}}
                                        Durasi: {{ $hours }} Jam @if ($minutes > 0)
                                            {{ $minutes }} Menit
                                        @endif
                                        <br>
                                        {{-- Kode Blade untuk menampilkan jam dan menit yang telah berlalu sejak waktu selesai --}}
                                        @if ($isElapsed && $elapsedHours > 0)
                                            @if ($bookingpaket->status == 'Sukses')
                                            <p class="text-success">Status Telah Sukses</p>
                                            @elseif ($bookingpaket->status == 'Batal')
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
                                                    Rp{{ number_format($bookingpaket->services->denda * $elapsedHours, 2, ',', '.') }}
                                                @endif
                                            </p>
                                            @endif
                                        @endif
                                    </td>
                                    @if ($bookingpaket->services->jenis_paket == 'Paket Perharga')
                                        @if ($isElapsed && $elapsedHours > 0)
                                            @if ($bookingpaket->status == 'Sukses')
                                            <td>Rp{{ number_format($bookingpaket->grand_total, 2, ',', '.') }}</td>
                                            @elseif ($bookingpaket->status == 'Batal')
                                            <td>Rp{{ number_format($bookingpaket->grand_total, 2, ',', '.') }}</td>
                                            @else
                                            <td>Rp{{ number_format($bookingpaket->services->price + $bookingpaket->services->denda * $elapsedHours, 2, ',', '.') }}
                                            </td>
                                            @endif
                                        @else
                                            <td>Rp{{ number_format($bookingpaket->grand_total, 2, ',', '.') }}</td>
                                        @endif
                                    @else
                                        @if ($isElapsed && $elapsedHours > 0)
                                            @if ($bookingpaket->status == 'Sukses')
                                                <td>Rp{{ number_format($bookingpaket->grand_total, 2, ',', '.') }}</td>
                                                @elseif ($bookingpaket->status == 'Batal')
                                                    <td>Rp{{ number_format($bookingpaket->grand_total, 2, ',', '.') }}</td>
                                            @else
                                                <td>Rp{{ number_format($bookingpaket->services->price * $hours + $bookingpaket->services->denda * $elapsedHours, 2, ',', '.') }}
                                                </td>
                                            @endif
                                        @else                                         
                                            <td>Rp{{ number_format($bookingpaket->grand_total, 2, ',', '.') }}</td>
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
                                                    @isset($bookingpaket->bukti_bayar)
                                                    <img class="w-100"
                                                        src="{{ asset('storage/'.$bookingpaket->bukti_bayar) }}" alt="">
                                                    <h5 style="text-align: center;margin:10px">Edit bukti Pembayaran</h5>
                                                    <form
                                                        action="{{ route('bookingpaketsadmin.uploadBukti', $bookingpaket->id) }}"
                                                        method="post" enctype="multipart/form-data">
                                                        @method('put')
                                                        @csrf
                                                        <div class="mb-3">
                                                            <input type="file" class="form-control" name="bukti_bayar">
                                                        </div>
                                                        <button
                                                            href=" https://api.whatsapp.com/send?phone=6281234567090&text=Nama,nomer lapangan berikut bukti pembayaran"
                                                            class="btn btn-success btn-block">{{ __('Edit bukti Pembayaran') }}</button>
                                                    </form>
                                                    @else
                                                    <p class="text-danger">
                                                        Belum ada bukti baya, silahkan bayar lalu kirim buktinya
                                                        disini
                                                    </p>
                                                    <form
                                                        action="{{ route('bookingpakets.uploadBukti', $bookingpaket->id) }}"
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
                                    <td>{{$bookingpaket->created_at}}</td>
                                    <td>{{ $bookingpaket->status }}</td>
                                    @php
                                        $total = $bookingpaket->services->price + $bookingpaket->services->denda * $elapsedHours;
                                    @endphp
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.bookingpaket.edit', $bookingpaket->id) }}"
                                                class="btn btn-info">
                                                <i class="fa fa-pencil-alt"></i>
                                            </a>
                                            <form onclick="return confirm('are you sure ? ')" class="d-inline"
                                                action="{{ route('admin.bookingpaket.destroy', $bookingpaket->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-danger"
                                                    style="border-top-left-radius: 0;border-bottom-left-radius: 0;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                            <a href="{{ url('admin/bookingpaket/nota_pemesanan/'.$bookingpaket->id) }}" target="_blank"
                                                class="btn btn-warning">
                                                {{-- Nota Pemesanan --}}
                                                <i class="fa fa-print"></i>
    
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="14" class="text-center">{{ __('Data Empty') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Content Row -->

    </div>
@endsection

@push('script-alt')
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
            let deleteButtonTrans = 'delete selected'
            let deleteButton = {
                text: deleteButtonTrans,
                url: "{{ route('admin.bookingpaket.mass_destroy') }}",
                className: 'btn-danger',
                action: function(e, dt, node, config) {
                    var ids = $.map(dt.rows({
                        selected: true
                    }).nodes(), function(entry) {
                        return $(entry).data('entry-id')
                    });
                    if (ids.length === 0) {
                        alert('zero selected')
                        return
                    }
                    if (confirm('are you sure ?')) {
                        $.ajax({
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                method: 'POST',
                                url: config.url,
                                data: {
                                    ids: ids,
                                    _method: 'DELETE'
                                }
                            })
                            .done(function() {
                                location.reload()
                            })
                    }
                }
            }
            dtButtons.push(deleteButton)
            $.extend(true, $.fn.dataTable.defaults, {
                order: [
                    [1, 'asc']
                ],
                pageLength: 50,
            });
            $('.datatable-bookingpaket:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })
    </script>
@endpush
