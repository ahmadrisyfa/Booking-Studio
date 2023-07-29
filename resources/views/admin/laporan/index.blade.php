@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->


        <!-- Content Row -->
        <div class="card">
            <div class="card-header py-3 d-flex">
                <h6 class="m-0 font-weight-bold text-primary">
                    {{ __('List Laporan') }}
                </h6>
            </div>
            <div class="col-md-12 col-sm-12">
                <form action="{{url('admin/laporan_booking/search')}}" method="post">
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
                                {{-- <a href="{{url('admin/cetak/transaksi')}}" style="font-weight:bold" class="btn btn-primary"><i class="bi bi-printer-fill" style="margin-right:10px"></i>Cetak</a> --}}        
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover datatable datatable-booking" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                                <th width="10">

                                </th>
                                <th>No</th>
                                <th>Nama Penyewa</th>
                                <th>Studio</th>
                                <th>Jam Mulai</th>
                                <th>Jam Berakhir</th>
                                {{-- <th>Total Jam</th> --}}
                                <th>Total Penyewa</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookings as $booking)
                                <tr data-entry-id="{{ $booking->id }}">
                                    <td>

                                    </td>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $booking->user->name }}</td>
                                    <td>{{ $booking->studios->names }}</td>
                                    <td>{{ Carbon\Carbon::parse($booking->time_from)->format('M, d D H:i:s') }}</td>
                                    <td>{{ Carbon\Carbon::parse($booking->time_to)->format('M, d D H:i:s') }}</td>
                                    {{-- @php
                                        $hour = date('h', strtotime(Carbon\Carbon::parse($booking->time_to)->format('H:i:s'))) - date('h', strtotime(Carbon\Carbon::parse($booking->time_from)->format('H:i:s')));
                                    @endphp
                                    <td>{{ $hour }} Jam</td> --}}
                                    <td>{{ number_format($booking->jml_org) }} Orang</td>
                                    <td>Rp{{ number_format($booking->grand_total,2, ',', '.') }}
                                    </td>
                                    <td>{{ $booking->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">{{ __('Data Empty') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tr>
                            <th>Total Pesanan</th>
                            <td>{{$jumlah}}</td>
                            <th>Total Harga Keseluruhan</th>
                            <td>Rp{{ number_format($totalharga, 2, ',', '.') }}</td>
                        </tr>   
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

            $.extend(true, $.fn.dataTable.defaults, {
                order: [
                    [1, 'asc']
                ],
                pageLength: 50,
            });
            $('.datatable-booking:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })
    </script>
@endpush
