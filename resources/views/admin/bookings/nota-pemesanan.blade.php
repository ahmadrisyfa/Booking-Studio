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
                <h4>Haii, Ini Adalah Nota Pemesanan {{$bookings->user->name}}</h4>
                <hr>
            </div>
            <div class="no_transaksi">
                <table>
                    <tr>
                        <td style="padding-right: 400px">No Transaksi</td>                       
                        <td>Tanggal Pemesanan</td>
                    </tr>
                    <tr>
                        <th>{{$bookings->kode}}</th>
                        <th>{{ Carbon\Carbon::parse($bookings->created_at)->format('M, d D H:i:s') }}</th>
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
                        <th>{{$bookings->user->name}}</th>
                        <th>Bank Mandiri a/n Contoh - 545673893938627</th>
                    </tr>
                    <tr>
                        <th class="text-success">{{$bookings->user->email}}</th>
                    </tr>
                </table>
            </div>
            <hr>
            <h5 class="text-success">Detail Studios</h5>
            <div class="no_transaksi">
                <table class="table">
                    <tr>
                        <th>Nama Studio</th>                       
                        <th>Harga</th>
                        <th>Harga Per orang</th>
                        <th>Denda</th>
                        {{-- <th>Jadwal</th> --}}
                        {{-- <th>Status</th> --}}
                        {{-- <th>Jumlah Jam</th> --}}
                        {{-- <th>Grand Total</th> --}}
                    </tr>
                @php
                    $startDateTime = new DateTime($bookings->time_from);
                    $endDateTime = new DateTime($bookings->time_to);
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
                    $total = $bookings->studios->price + $bookings->studios->denda * $elapsedHours;
                @endphp
                    <tr>
                        <td>{{$bookings->studios->names}}</td>
                        <td>Rp.{{ number_format($bookings->studios->price, 2, ',', '.') }}</td>
                        <td>Rp.{{ number_format($bookings->studios->org, 2, ',', '.') }}</td>
                        <td>Rp.{{ number_format($bookings->studios->denda, 2, ',', '.') }}</td>
                        {{-- <td>{{$bookings->time_from}} <br>                        
                            {{$bookings->time_to}}
                        </td> --}}
                        {{-- <td>{{ $bookings->status }}</td> --}}
                           {{-- <td>
                            Durasi: {{ $hours }} Jam @if ($minutes > 0)
                            {{ $minutes }} Menit
                                @endif
                     </td>
                            @if ($isElapsed && $elapsedHours > 0)
                            @if ($bookings->status == 'Sukses')
                                <td>Rp{{ number_format($bookings->grand_total, 2, ',', '.') }}</td>
                            @elseif ($bookings->status == 'Batal')
                                <td>Rp{{ number_format($bookings->grand_total, 2, ',', '.') }}</td>
                            @else
                                <td>Rp{{ number_format($bookings->studios->price * $bookings->jml_org  * $hours + $bookings->studios->denda * $elapsedHours, 2, ',', '.') }}
                                </td>
                            @endif
                        @else
                            <td>Rp{{ number_format($bookings->studios->price * $bookings->jml_org * $hours,  2, ',', '.') }}</td>
                        @endif --}}
                    </tr>
                   
                </table>
            </div>

            {{-- <hr> --}}
            <h5 class="text-success">Detail Pesanan</h5>
            <div class="no_transaksi">
                <table class="table">
                    <tr>
                        <th>Nama Studio</th>                       
                        {{-- <th>Harga</th>
                        <th>Harga Per orang</th>
                        <th>Denda</th> --}}
                        <th>Jumlah Orang</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th>Jumlah Jam</th>
                        <th>Bukti Pembayaran</th>
                        <th>Grand Total</th>
                    </tr>
                @php
                    $startDateTime = new DateTime($bookings->time_from);
                    $endDateTime = new DateTime($bookings->time_to);
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
                    $total = $bookings->studios->price + $bookings->studios->denda * $elapsedHours;
                @endphp
                    <tr>
                        <td>{{$bookings->studios->names}}</td>
                        {{-- <td>Rp.{{ number_format($bookings->studios->price, 2, ',', '.') }}</td>
                        <td>Rp.{{ number_format($bookings->studios->org, 2, ',', '.') }}</td>
                        <td>Rp.{{ number_format($bookings->studios->denda, 2, ',', '.') }}</td> --}}
                        <td>{{$bookings->jml_org}}</td>
                        <td>{{ Carbon\Carbon::parse($bookings->time_from)->format('M, d D H:i:s') }} <br>
                            {{ Carbon\Carbon::parse($bookings->time_to)->format('M, d D H:i:s') }}</td>
                        <td>{{ $bookings->status }}</td>
                        <td>
                            Durasi: {{ $hours }} Jam @if ($minutes > 0)
                            {{ $minutes }} Menit
                                @endif
                        </td>
                        <td>
                            @isset($bookings->bukti_bayar)
                            <img  style="width:100px" src="{{ asset('storage/'.$bookings->bukti_bayar) }}" alt="">
                            @else
                            <p class="text-danger">Belum Menyelesaikan Pembayaran</p>
                            @endisset
                        </td>
                            @if ($isElapsed && $elapsedHours > 0)
                            @if ($bookings->status == 'Sukses')
                                <td class="text-success">Rp.{{ number_format($bookings->grand_total, 2, ',', '.') }}</td>
                            @elseif ($bookings->status == 'Batal')
                                <td class="text-success">Rp.{{ number_format($bookings->grand_total, 2, ',', '.') }}</td>
                            @else
                                <td class="text-success">Rp.{{ number_format($bookings->studios->price * $bookings->jml_org  * $hours + $bookings->studios->denda * $elapsedHours, 2, ',', '.') }}
                                </td>
                            @endif
                        @else
                            <td class="text-success">Rp.{{ number_format($bookings->studios->price * $bookings->jml_org * $hours,  2, ',', '.') }}</td>
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