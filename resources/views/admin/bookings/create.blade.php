@extends('layouts.admin')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->


    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Content Row -->
    <div class="card shadow">
        <div class="card-header">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">{{ __('create booking') }}</h1>
                <a href="{{ route('admin.bookings.index') }}" class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.bookings.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="studios_id">{{ __('Nomer Studio') }}</label>
                    <select name="studios_id" id="studios_id" required class="form-control">
                        @foreach($studios as $studio)
                        <option {{ $studiosString == $studio->String ? 'selected' : null }} value="{{ $studio->id }}">
                            {{ $studio->names }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="price">{{ __('Jumlah Orang') }}</label>
                    <input type="number" class="form-control" required  id="jml_org" placeholder="{{ __('jumlah org') }}" name="jml_org" value="{{ old('jml_org') }}" />
                </div>
                <div class="form-group">
                    <label for="time_from">{{ __('Dari Jam') }}</label>
                    <input type="text" class="form-control datetimepicker" required id="time_from" name="time_from" value="{{ old('time_from') }}" />
                </div>
                <div class="form-group">
                    <label for="time_to">{{ __('Sampai Jam') }}</label>
                    <input type="text" class="form-control datetimepicker" required id="time_to" name="time_to" value="{{ old('time_to') }}" />
                </div>
                <div class="form-group">
                    <label for="status">{{ __('Status') }}</label>
                    <select name="status" id="status" required class="form-control">
                        <option value="0">Menunggu konfirmasi</option>
                        <option value="1">Booked</option>
                        <option value="2">Sukses</option>
                        <option value="3">Batal</option>
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
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">
@endpush

@push('script-alt')
<script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
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
</script>
@endpush