@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->


    <!-- Content Row -->
    <div class="card">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ __('Paket') }}
            </h6>
            <div class="ml-auto">
                @can('booking_create')
                <a href="{{ route('admin.services.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">{{ __('Buat paket') }}</span>
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-service"
                    cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>No</th>
                            <th>Nama</th>
                            <th>jenis paket</th>
                            <th>Harga</th>
                            <th>Gambar</th>
                            <th>Denda</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                        <tr data-entry-id="{{ $service->id }}">
                            <td>

                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->jenis_paket }}</td>
                            <td>Rp{{ number_format($service->price,2,',','.') }}</td>
   
                            @if ($service->image)
                            @php
                                $imagePaths = json_decode($service->image);
                                $firstImagePath = reset($imagePaths);
                            @endphp
                                    <td> <img src="{{ asset('storage/image-services/' . $firstImagePath) }}" alt="Service Image" width="200"></td>
                            @endif
                            <td>Rp{{ number_format($service->denda,2,',','.') }}</td>
                            <td>{{ $service->status }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.services.edit', $service->id) }}" class="btn btn-info">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form onclick="return confirm('are you sure ? ')" class="d-inline"
                                        action="{{ route('admin.services.destroy', $service->id) }}" method="POST">
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
                            <td colspan="7" class="text-center">{{ __('Data Empty') }}</td>
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
        url: "{{ route('admin.services.mass_destroy') }}",
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
    $('.datatable-service:not(.ajaxTable)').DataTable({
        buttons: dtButtons
    })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})
</script>
@endpush