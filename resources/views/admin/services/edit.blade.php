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
                <h1 class="h3 mb-0 text-gray-800">{{ __('Edit paket') }}</h1>
                <a href="{{ route('admin.services.index') }}"
                    class="btn btn-primary btn-sm shadow-sm">{{ __('Go Back') }}</a>
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.services.update', $service->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('put')<div class="form-group">
                    <label for="services_id">Jenis Paket</label>
                    <select name="jenis_paket" id="jenis_paket" class="form-control">
                        <option @if($service->jenis_paket == 'Paket Perjam') selected @endif  value="Paket Perjam">Paket Perjam</option>
                        <option @if($service->jenis_paket == 'Paket Perharga') selected @endif  value="Paket Perharga">Paket Perharga</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">{{ __('name') }}</label>
                    <input type="text" class="form-control" id="name" placeholder="{{ __('name') }}" name="name"
                        value="{{ old('name', $service->name) }}" />
                </div>
                <div class="form-group">
                    <label for="price">{{ __('Harga ') }}</label>
                    <input type="number" class="form-control" id="price" placeholder="{{ __('price') }}" name="price"
                        value="{{ old('price', $service->price) }}" />
                </div>
                <div class="form-group">
                    <label for="jam_paket">{{ __('jumlah jam') }}</label>
                    <input type="number" class="form-control" id="jam_paket" placeholder="{{ __('jam_paket') }}"
                        name="jam_paket" value="{{ old('jam_paket',$service->jam_paket) }}" />
                    <span class="text-secondary">Jika kosong maka user bisa mengisi jam terserah</span>
                </div>
                <div class="form-group">
                    <label for="name">{{ __('Denda') }}</label>
                    <input type="text" class="form-control" id="denda" placeholder="{{ __('denda') }}" name="denda"
                        value="{{ old('denda', $service->denda) }}" />
                </div>
                <div class="form-group">
                    @if(json_decode($service->image))
                    <div>
                        <label>Current Images:</label>
                        @foreach(json_decode($service->image) as $image)
                            <div>
                                <img src="{{ asset('storage/image-services/' . $image) }}" alt="Service Image" style="max-width: 200px;margin-bottom:10px">
                            </div>
                        @endforeach
                    </div>
                    @endif
                    <label for="photo">Photo</label>
                    <input type="file" class="form-control" id="image" placeholder="{{ __('image') }}" name="image[]" multiple>

                    @if($errors->has('image'))
                    <em class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </em>
                    @endif
                </div>
                <div class="form-group">
                    <label for="status">{{ __('Status') }}</label>
                    <select name="status" id="status" class="form-control">
                        <option {{ $service->status == 'Active' ? 'selected' : null }} value="1">Active</option>
                        <option {{ $service->status == 'In Active' ? 'selected' : null }} value="0">In Active</option>
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
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
@endpush

@push('script-alt')
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script>
Dropzone.options.photoDropzone = {
    url: "{{ route('admin.services.storeMedia') }}",
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
        size: 2,
        width: 4096,
        height: 4096
    },
    success: function(file, response) {
        $('form').find('input[name="photo"]').remove()
        $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
    },
    removedfile: function(file) {
        file.previewElement.remove()
        if (file.status !== 'error') {
            $('form').find('input[name="photo"]').remove()
            this.options.maxFiles = this.options.maxFiles + 1
        }
    },
    init: function() {
        @if(isset($services) && $services->photo)
        var file = {
            !!json_encode($services->photo) !!
        }
        this.options.addedfile.call(this, file)
        this.options.thumbnail.call(this, file, "{{ $services->photo->getUrl() }}")
        file.previewElement.classList.add('dz-complete')
        $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
        this.options.maxFiles = this.options.maxFiles - 1
        @endif
    },
    error: function(file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }
        return _results
    }
}
</script>
@endpush