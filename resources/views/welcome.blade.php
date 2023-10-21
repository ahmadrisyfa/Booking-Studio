@extends('layouts.user')
@section('content')
<style>   
    .footer {
        padding: 20px;
        color: #ADB5BD;
    }

    .footer hr {
        background-color: #ADB5BD;
        margin-top: 10px;
        margin-bottom: 10px;

    }

    .footer-logo {
        width: 50px;
        display: inline-block;
        vertical-align: middle;
    }  
</style>
<div class="jumbotron text-white text-center" style="background: linear-gradient(
    rgba(0, 0, 0, 0.7),
    rgba(0, 0, 0, 0.7)
  ),url('img/2020-10-20.jpg');background-repeat: no-repeat;background-position: center;background-size: cover;">
    <div class="container" style="padding-top: 200px;padding-bottom: 200px">
        <h1 class="display-4 fw-bold">Selamat Datang di Matrix Studio Venue</h1>
        <p class="lead">Menyediakan layanan booking studio untuk kegiatan anda</p>
        <hr class="my-4">
        <p>Memberikan layanan dan harga terbaik untuk anda dalam membuat lagu</p>
        @auth
        <a class="btn btn-primary" href="#studio" role="button">Booking Studio</a>
        @else
        <p>silahkan login untuk boking studio</p>
        <div class="d-flex justify-content-center">
            <a href="{{ route('register') }}" class="btn btn-primary mr-2">Register</a>
            <a href="{{ route('login') }}" class="btn btn-success">Login</a>
        </div>
        @endauth
    </div>
</div>
<div class="my-5" id="services">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="fw-bold">Layanan Kami</h3>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card text-center rounded-2">
                    <div class="card-body">
                        <div class="icon-area">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h2>Penyewaan Studio</h2>
                        <p>Menyediakan layanan booking studio untuk kegiatan anda</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-center rounded-2">
                    <div class="card-body">
                        <div class="icon-area">
                            <i class="fa fa-music"></i>
                        </div>
                        <h2>Layanan bervariasi</h2>
                        <p>Memberikan layanan sesuai dengan kebutuhan pelanggan</p>
                    </div>
                </div>
            </div>
        </div>
        <h4 class="text-center mt-4">PERATURAN PENYEWAAN STUDIO MUSIK</h4>
        <p class="text-center mt-2" style="color: black">
            <ol class="text-center text-success">
                <li>
                    Jika waktu penyewaan sudah habis, tetapi penyewa masih bermain musik didalam maka akan dikenakan denda 
                </li>
                <li>
                    Tidak boleh membawa makanan & minuman kedalam studio musik
                </li>
                <li>
                    Tidak boleh merokok didalam studio musik
                </li>
            </ol>
        </p>
        <hr>
        <h4 class="text-center">Jam Operasional</h4>
        <p class="text-center text-success">Pukul: 09.00 - 21.00</p>
        <hr>
    </div>
</div>
{{-- <section class="services-area" id="services">
        <h3 class="header-text">Layanan Kami</h3>
        <div class="text-center">Kami Menyediakan Layanan Terbaik Untuk Anda </div>
        <div class="content-area">
            <div class="single-service">
                <div class="icon-area">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <h2>Penyewaan Studio</h2>
                <p>Menyediakan layanan booking studio untuk kegiatan anda</p>
            </div>
            <div class="single-service">
                <div class="icon-area">
                    <i class="fa fa-music"></i>
                </div>
                <h2>Layanan bervariasi</h2>
                <p>Memberikan layanan yang lengkap sesuai dengan kebutuhan pelanggan</p>
            </div>
        </div>
    </section> --}}
{{-- <div class="card">
            <div class="card-header">
                Jadwal Studio
            </div>

            <div class="card-body">
                <link rel='stylesheet'
                href='https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.1.0/fullcalendar.min.css' />

                <div id='calendar'></div>
            </div>
        </div><br> --}}
<div class="mt-5 mb-1" id="studio">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h3 class="fw-bold">Daftar Studio</h3>
            </div>
            @foreach ($studios as $studios)
            <div class="modal fade" id="gambarstudios{{$studios->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="gambarstudiosLabel{{$studios->id}}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="gambarstudiosLabel{{$studios->id}}">Image Studio</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                        <div id="carouselExampleControls{{$studios->id}}" class="carousel slide" data-ride="carousel">
                            <div class="carousel-inner">
                                @foreach (json_decode($studios->image) as $index => $imagePath)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    <div style="display: flex; justify-content: center; align-items: center;">
                                    <img src="{{ asset('storage/image-studios/' . $imagePath) }}" class="d-block" style="width:400px" alt="Service Image">
                                    </div>
                                </div>
                                @endforeach
                            </div>
            
                            <a class="carousel-control-prev" href="#carouselExampleControls{{$studios->id}}" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgb(52, 52, 52)"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls{{$studios->id}}" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true" style="background-color:  rgb(52, 52, 52)"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                  </div>
                </div>
            </div>
            <div class="col-lg-4 mb-5">
                <div class="card" style="width: 18rem;">
                    <span data-toggle="modal" data-target="#gambarstudios{{$studios->id}}">
                        @if ($studios->image)
                        @php
                            $imagePaths = json_decode($studios->image);
                            $firstImagePath = reset($imagePaths);
                        @endphp
                                <td> <img src="{{ asset('storage/image-studios/' . $firstImagePath) }}" alt="Studios Image" width="250" style="cursor: pointer"></td>
                        @endif
                      </span>
                    <div class="card-body">
                        <h5 class="card-title">{{ $studios->names }}</h5>
                        <p class="card-text">Harga : Rp{{ number_format($studios->price, 2, ',', '.') }} / Jam
                        </p>
                        <a href="{{url('booking/create/'.$studios->id)}}"
                            class="btn btn-primary">Booking</a>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="col-12">
                <h3 class="fw-bold">Daftar Paket</h3>
            </div>
            {{-- <div class="collumn"> --}}
                @foreach ($services as $services)
                <div class="modal fade" id="gambarservices{{$services->id}}" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="gambarservicesLabel{{$services->id}}" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="gambarservicesLabel{{$services->id}}">Image Service</h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="modal-body">
                            <div id="carouselExampleControls{{$services->id}}" class="carousel slide" data-ride="carousel">
                                <div class="carousel-inner">
                                    @foreach (json_decode($services->image) as $index => $imagePath)
                                    <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                        <div style="display: flex; justify-content: center; align-items: center;">
                                        <img src="{{ asset('storage/image-services/' . $imagePath) }}" class="d-block" style="width:400px" alt="Service Image">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                
                                <a class="carousel-control-prev" href="#carouselExampleControls{{$services->id}}" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true" style="background-color: rgb(52, 52, 52)"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#carouselExampleControls{{$services->id}}" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true" style="background-color:  rgb(52, 52, 52)"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-5">
                    <div class="card" style="width: 18rem;">
                        <span data-toggle="modal" data-target="#gambarservices{{$services->id}}">
                            @if ($services->image)
                            @php
                                $imagePaths = json_decode($services->image);
                                $firstImagePath = reset($imagePaths);
                            @endphp
                                    <td> <img src="{{ asset('storage/image-services/' . $firstImagePath) }}" alt="Service Image" width="250" style="cursor: pointer"></td>
                            @endif
                          </span>


                        <div class="card-body">
                            <p class="badge bg-warning">{{$services->jenis_paket}}</p>
                            <h5 class="card-title">{{$services->name}}</h5>
                            <p class="card-text">@if($services->jam_paket != '')Durasi:
                                {{$services->jam_paket}} Jam @endif
                            </p>
                            <p class="card-text">Harga : Rp{{ number_format($services->price, 2, ',', '.') }}
                            </p>
                            <a href="{{url('bookingpakets/create/'.$services->id)}}"
                                class="btn btn-primary">Booking</a>
                        </div>
                    </div>
                </div>
                @endforeach
            {{-- </div> --}}
        </div>
    </div>
    <br><br>
</div>

<div class="bg-secondary py-5">
    <div class="container text-white text-center">
        <h2 class="header-text font-weight-bold">Matrix Studio Venue</h2>
        <p>Lokasi dari Matrix Studio </p>
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.921973480131!2d107.6498847104652!3d-6.899935093070524!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e79143ab6c79%3A0x8e37ffbc61d6b405!2sMatrix%20Music%20Studio%20%26%20Cafe!5e0!3m2!1sen!2sid!4v1687323210298!5m2!1sen!2sid"
            width="1110" height="420" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
</div>
<div class="footer-website" style="background-color:#151E28;">
    <footer class="footer">     
        <div class="container pt-3">
            <div class="row">
                <div class="col-md-12" style="text-align: center">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <h3 style="color:white;margin-bottom:10px">Contact Us</h3>
                        </div>
                        <div class="col-md-6">                                               
                            <p><i class="bi bi-geo-alt pr-2"></i>Alamat:  Surapati Core, Jl. Anggrek Boulevard no.27, Pasirlayung, Jl. Phh. Mustofa No.153, Pasirlayung, Kec. Cibeunying Kidul, Kota Bandung, Jawa Barat 40192</p>
                            <p><i class="bi bi-geo-alt pr-2"></i>Email: ilhamalamsyah171@gmail.com</p>

                        </div>
                        <div class="col-md-6">

                            <p>No Telepon : 0857-2002-2188</p>
                            <p>No Rekening: 54678724648844</p>
                        </div>
                    </div>

                </div>
                {{-- <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <ul style="list-style:none">
                                <li><a style="color: #ADB5BD;" href="#">Home</a></li>
                                <li><a style="color: #ADB5BD;" href="#">Tanya Jawab</a></li>
                                <li><a style="color: #ADB5BD;" href="#">Hubungi Kami</a></li>
                                <li><a style="color: #ADB5BD;" href="#">Produk Rekomendasi</a></li>
                                <li><a style="color: #ADB5BD;" href="#">Masuk</a></li>
                                <li><a style="color: #ADB5BD;" href="#">Daftar</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul style="list-style:none">
                                <li><a style="color: #ADB5BD;">Original Tart</a></li>
                                <li><a style="color: #ADB5BD;">Fruit Tart</a></li>
                                <li><a style="color: #ADB5BD;">Hampers</a></li>
                                <li><a style="color: #ADB5BD;">Topping</a></li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <ul style="list-style:none">
                                <li><a href="#" style="color: #ADB5BD;"><i
                                            class="bi bi-facebook pr-2"></i>Facebook</a></li>
                                <li><a href="#" style="color: #ADB5BD;"><i
                                            class="bi bi-instagram pr-2"></i>Instagram</a></li>
                                <li><a href="#" style="color: #ADB5BD;"><i
                                            class="bi bi-twitter pr-2"></i>Twitter</a></li>
                            </ul>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="container mt-5">
            <div class="row justify-content-between d-flex">
                <div class="col-md-6">
                 
                    <p>© Copyright 2023 | Ilham Alamsyah </p>
                </div>
                <div class="col-md-6 text-right">
                    <p><a href="#" style="color: #ADB5BD;">FAQ | Kontak Kami</a></p>
                </div>
            </div>
        </div>

    </footer>
</div>
@endsection