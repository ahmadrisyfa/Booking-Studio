<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Nota Pemesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <style>
       
        body {
            background-color: #47b375;
            display: flex;
            justify-content: center;
            /* align-items: center; */
            height: auto;
            margin-top: 30px;
            font-family:  sans-serif;
        }

        .card {
            width: 800px;
            /* text-align: center; */
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 20px;
        }  
        .no_transaksi {
        display: flex;
        justify-content: space-between;
        /* padding: 10px; */
        }   
        #printButton {
            display: block;
        }

        /* Media query untuk mencetak halaman */
        @media print {
            /* Sembunyikan tombol cetak saat mencetak */
            #printButton {
                display: none;
            }
        }
    </style>
</head>
<body>
    
    <div class="card">
        <button id="printButton" class="btn btn-success btn-sm">Cetak</button>
        <div class="card-body">
            <div class="header">
                <h4>Haii, Ini Adalah Nota Pemesanan {{$bookingpaket->user->name}}</h4>
                <hr>
            </div>
            <div class="no_transaksi">
                <table>
                    <tr>
                        <td style="padding-right: 400px">No Transaksi</td>                       
                        <td>Tanggal Pemesanan</td>
                    </tr>
                    <tr>
                        <th>{{$bookingpaket->kode}}</th>
                        <th>{{ Carbon\Carbon::parse($bookingpaket->created_at)->format('M, d D H:i:s') }}</th>
                    </tr>
                </table>
            </div>
            <hr>
            <div class="no_transaksi" style="margin-bottom: 60px">
                <table>
                    <tr>
                        <td style="padding-right: 310px">Pelanggan</td>                       
                        <td>Pembayaran Ke</td>
                    </tr>
                    <tr>
                        <th>{{$bookingpaket->user->name}}</th>
                        <th>Bank Mandiri a/n Contoh - 545673893938627</th>
                    </tr>
                    <tr>
                        <th class="text-success">{{$bookingpaket->user->email}}</th>
                    </tr>
                </table>
            </div>
            <hr>
            <h5 class="text-success">Detail Services {{$bookingpaket->services->jenis_paket}}</h5>
            <div class="no_transaksi">
                <table class="table">
                    <tr>
                        <th>Nama Services</th>                       
                        <th>Harga</th>
                        <th>Jenis Paket</th>
                        <th>Jam Paket</th>
                        <th>Denda</th>
                        {{-- <th>Jadwal</th> --}}
                        {{-- <th>Status</th> --}}
                        {{-- <th>Jumlah Jam</th> --}}
                        {{-- <th>Grand Total</th> --}}
                    </tr>
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
                    $total = $bookingpaket->services->price + $bookingpaket->services->denda * $elapsedHours;
                @endphp
                    <tr>
                        <td>{{$bookingpaket->services->name}}</td>
                        <td>Rp.{{ number_format($bookingpaket->services->price, 2, ',', '.') }}</td>
                        <td><span class="btn btn-warning btn-sm">{{$bookingpaket->services->jenis_paket}}</span></td>
                        <td>{{$bookingpaket->services->jam_paket}}</td>
                        <td>Rp.{{ number_format($bookingpaket->services->denda, 2, ',', '.') }}</td>
                        {{-- <td>{{$bookingpaket->time_from}} <br>                        
                            {{$bookingpaket->time_to}}
                        </td> --}}
                        {{-- <td>{{ $bookingpaket->status }}</td> --}}
                           {{-- <td>
                            Durasi: {{ $hours }} Jam @if ($minutes > 0)
                            {{ $minutes }} Menit
                                @endif
                     </td>
                            @if ($isElapsed && $elapsedHours > 0)
                            @if ($bookingpaket->status == 'Sukses')
                                <td>Rp{{ number_format($bookingpaket->grand_total, 2, ',', '.') }}</td>
                            @elseif ($bookingpaket->status == 'Batal')
                                <td>Rp{{ number_format($bookingpaket->grand_total, 2, ',', '.') }}</td>
                            @else
                                <td>Rp{{ number_format($bookingpaket->services->price * $bookingpaket->jml_org  * $hours + $bookingpaket->services->denda * $elapsedHours, 2, ',', '.') }}
                                </td>
                            @endif
                        @else
                            <td>Rp{{ number_format($bookingpaket->services->price * $bookingpaket->jml_org * $hours,  2, ',', '.') }}</td>
                        @endif --}}
                    </tr>
                   
                </table>
            </div>

            {{-- <hr> --}}
            <h5 class="text-success">Detail Pesanan</h5>
            <div class="no_transaksi">
                <table class="table">
                    <tr>
                        <th>Nama Services</th>                       
                        {{-- <th>Harga</th>
                        <th>Harga Per orang</th>
                        <th>Denda</th> --}}
                        {{-- <th>Jumlah Orang</th> --}}
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Jumlah Jam</th>
                        <th>Bukti Pembayaran</th>
                        <th>Grand Total</th>
                    </tr>
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
                    <tr>
                        <td>{{$bookingpaket->services->name}}</td>
                        {{-- <td>Rp.{{ number_format($bookingpaket->services->price, 2, ',', '.') }}</td>
                        <td>Rp.{{ number_format($bookingpaket->services->org, 2, ',', '.') }}</td>
                        <td>Rp.{{ number_format($bookingpaket->services->denda, 2, ',', '.') }}</td> --}}
                        {{-- <td>{{$bookingpaket->jml_org}}</td> --}}
                        <td>{{ Carbon\Carbon::parse($bookingpaket->time_from)->format('M, d D H:i:s') }} <br>
                        {{ Carbon\Carbon::parse($bookingpaket->time_to)->format('M, d D H:i:s') }}</td>
                        <td>{{ $bookingpaket->status }}</td>
                        <td>
                            Durasi: {{ $hours }} Jam @if ($minutes > 0)
                            {{ $minutes }} Menit
                                @endif
                        </td>
                        <td>
                            @isset($bookingpaket->bukti_bayar)
                            <img  style="width:100px" src="{{ asset('storage/'.$bookingpaket->bukti_bayar) }}" alt="">
                            @else
                            <p class="text-danger">Belum Menyelesaikan Pembayaran</p>
                            @endisset
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
                       
                    </tr>
                   
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>
    <script>
        function printPage() {
            window.print();
        }
    
        var printButton = document.getElementById("printButton");
        printButton.addEventListener("click", printPage);
    </script>
</body>
</html>