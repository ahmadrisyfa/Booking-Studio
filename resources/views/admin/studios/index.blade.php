@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->


    <!-- Content Row -->
    <div class background="card">
        <div class="card-header py-3 d-flex">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ __('List Studio') }}
            </h6>
            <div class="ml-auto">
                @can('studios_create')
                <a href="{{ route('admin.studios.create') }}" class="btn btn-primary">
                    <span class="icon text-white-50">
                        <i class="fa fa-plus"></i>
                    </span>
                    <span class="text">{{ __('Buat Studio') }}</span>
                </a>
                @endcan
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover datatable datatable-studios"
                    cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th width="10">

                            </th>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Harga per jam</th>
                            <th>Harga per orang</th>
                            <th>Gambar</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($studios as $studio)
                        <tr data-entry-id="{{ $studio->id }}">
                            <td>

                            </td>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $studio->names }}</td>
                            <td>Rp{{ number_format($studio->price,2,',','.') }}</td>
                            <td>Rp{{ number_format($studio->org,2,',','.') }}</td>
                            @if ($studio->image)
                            @php
                                $imagePaths = json_decode($studio->image);
                                $firstImagePath = reset($imagePaths);
                            @endphp
                                    <td> <img src="{{ asset('storage/image-studios/' . $firstImagePath) }}" alt="studio Image" width="200"></td>
                            @endif
                            <td>{{ $studio->status }}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <a href="{{ route('admin.studios.edit', $studio->id) }}"
                                        class="btn btn-sm btn-info">
                                        <i class="fa fa-pencil-alt"></i>
                                    </a>
                                    <form onclick="return confirm('are you sure ? ')" class="d-inline"
                                        action="{{ route('admin.studios.destroy', $studio->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-sm btn-danger"
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
        url: "{{ route('admin.studios.mass_destroy') }}",
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
    $('.datatable-studios:not(.ajaxTable)').DataTable({
        buttons: dtButtons
    })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})
</script>
@endpush