@extends('layouts.user')
@section('content')
<div class="container my-4" style="height: 80vh">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Notifikasi Saya
                </div>
                <div class="card-body">
                    @foreach ($notifikasi as $value)                    
                    <div class="alert alert-primary alert-dismissible fade show" role="alert">
                        <span class="text-primary font-weight-bold" style="text-transform: capitalize">{{$value->user->name}} ( Anda )</span> Telah {{$value->text}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endforeach
                </div>
            </div>
        </div>
    </div>
</div>




<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css">

@endsection
@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js">
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