@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->


    <!-- Content Row -->
    <div class="card">
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
                            <th>Nama Service</th>
                            <th>Jenis Paket</th>
                            <th>Jam Mulai</th>
                            <th>Jam Berakhir</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bookingpaket as $bookingpaket)
                        <tr data-entry-id="{{ $bookingpaket ->id }}">
                            <td>

                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $bookingpaket->kode }}</td>
                            <td>{{ $bookingpaket->user->name }}</td>
                            <td>{{ $bookingpaket->services->name }}</td>
                            <td><button class="btn btn-info">{{ $bookingpaket->services->jenis_paket }}</button></td>
                            <td>{{ Carbon\Carbon::parse($bookingpaket->time_from)->format('M, d D H:i:s') }}</td>
                            <td>{{ Carbon\Carbon::parse($bookingpaket->time_to)->format('M, d D H:i:s') }}</td>
                            @if ($bookingpaket->services->jenis_paket == "Paket Perharga")
                            <td>Rp{{ number_format($bookingpaket->services->price  + $bookingpaket->services->denda,2,',','.')  }}</td>                                
                            @else                                
                            <td>Rp{{ number_format($bookingpaket->services->price  * $bookingpaket->services->jam_paket +  $bookingpaket->services->denda,2,',','.')  }}</td>                                                          
                            @endif
                            <td>{{ $bookingpaket->status }}</td>
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
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('Data Empty') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ __('List Booking Paket Hari Ini') }}
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
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bphariini as $bookingpaket)
                        <tr data-entry-id="{{ $bookingpaket ->id }}">
                            <td>
                                @php
                                $startDateTime = new DateTime($bookingpaket->time_from);
                                $endDateTime = new DateTime($bookingpaket->time_to);
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
                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $bookingpaket->kode }}</td>
                            <td>{{ $bookingpaket->user->name }}</td>
                            <td>{{ $bookingpaket->services->name }}</td>
                            <td><button class="btn btn-info">{{ $bookingpaket->services->jenis_paket }}</button></td>
                            <td>{{ Carbon\Carbon::parse($bookingpaket->time_from)->format('M, d D H:i:s') }}</td>
                            <td>{{ Carbon\Carbon::parse($bookingpaket->time_to)->format('M, d D H:i:s') }}</td>
                            {{-- <td>Rp @if ($elapsedHours >
                                0){{ number_format($bookingpaket->services->denda * $elapsedHours + $bookingpaket->grand_total,2,',','.')  }}@else
                                {{ number_format($bookingpaket->grand_total,2,',','.')  }}@endif
                            </td> --}}
                            <td>Rp{{ number_format($bookingpaket->services->price,2,',','.')  }}</td>                                
                            <td>
                                @php

                                @endphp
                                {{-- Kode Blade untuk menampilkan jumlah jam --}}
                                Durasi: {{ $hours }} Jam @if ($minutes > 0){{ $minutes }} Menit @endif
                                <br>
                                {{-- Kode Blade untuk menampilkan jam dan menit yang telah berlalu sejak waktu selesai --}}
                                @if ($isElapsed && ($elapsedHours > 0))
                                <p class="text-danger">
                                    Waktu telah berlalu sejak selesai:
                                    @if ($elapsedHours > 0)
                                    {{ $elapsedHours }} jam
                                    @if ($elapsedMinutes > 0)
                                    {{ $elapsedMinutes }} menit
                                    @endif
                                    Denda:
                                    Rp{{ number_format($bookingpaket->services->denda * $elapsedHours,2,',','.')  }}
                                    @endif
                                </p>
                                @endif
                            </td>
                            @if ($bookingpaket->services->jenis_paket == "Paket Perharga")
                            <td>Rp{{ number_format($bookingpaket->services->price  + $bookingpaket->services->denda  * $elapsedHours,2,',','.')  }}</td>                                
                            @else                                
                            <td>Rp{{ number_format($bookingpaket->services->price  * $bookingpaket->services->jam_paket +  $bookingpaket->services->denda  * $elapsedHours,2,',','.')  }}</td>                                                          
                            @endif
                            <td>{{$bookingpaket->status}}</td>
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
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('Data Empty') }}</td>
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