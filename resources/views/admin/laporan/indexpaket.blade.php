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
            <form action="{{url('admin/laporan/search')}}" method="post">
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
                <div class="col-md-3 col-sm-3 ">
                    Plih Paket
                    <select name="jenis_paket" id="jenis_paket" class="form-control">
                        <option value="" style="text-align: center" {{ empty(request('jenis_paket')) ? 'selected' : '' }}>-- Pilih Semua  Paket --</option>
                        <option value="Paket Perjam" {{ request('jenis_paket') == 'Paket Perjam' ? 'selected' : '' }}>Paket Perjam</option>
                        <option value="Paket Perharga" {{ request('jenis_paket') == 'Paket Perharga' ? 'selected' : '' }}>Paket Perharga</option>
                    </select>
                </div>
                <div class="col-md-12" style="margin-top: 18px;">        
                    <button class="btn btn-info" style="font-weight:bold" type="submit"><i class="fa fa-search"
                            style="margin-right:8px"></i>Cari</button>
                            <button type="button" class="btn btn-success" onclick="cetaktable()">Cetak</button>
                            <button type="button" class="btn btn-info" onclick="download()">Download</button>
                            <button type="button" class="btn btn-warning" onclick="downloadAsPdf()">Download Pdf</button>    
                </div>
            </form>
        </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped" cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>                                                             
                                <th>No</th>
                                <th>Nama Penyewa</th>
                                <th>Nama paket</th>
                                <th>Jenis paket</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bookingpaket as $bookingpaket)
                            <tr data-entry-id="{{ $bookingpaket->id }}">                                                           
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $bookingpaket->user->name }}</td>
                                    <td>{{ $bookingpaket->services->name }}</td>
                                    <td>{{ $bookingpaket->services->jenis_paket }}</td>
                                    <td>{{ Carbon\Carbon::parse($bookingpaket->time_from)->format('M, d D H:i:s') }}</td>
                                    <td>{{ Carbon\Carbon::parse($bookingpaket->time_to)->format('M, d D H:i:s') }}</td>
                                    <td>Rp{{ number_format($bookingpaket->grand_total, 2, ',', '.') }}</td>
                                    <td>{{ $bookingpaket->status }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">{{ __('Data Empty') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tr>
                            <th colspan="2">Total Pesanan</th>
                            <td>{{$jumlah}}</td>
                            <th colspan="3">Total Harga Keseluruhan</th>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    function downloadAsPdf() {
        const table = document.getElementById('example1');

        // Set options for html2pdf
        const options = {
            margin: 1,
            filename: 'filtered_data.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' }
        };

        // Create a new html2pdf instance
        html2pdf().from(table).set(options).save();
    }
</script>

<script>
    function cetaktable() {
        const table = document.getElementById('example1'); 
        const newWindow = window.open('', '_blank');
        const style = `
    <style>
        body { margin: 60px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; }
        th { background-color: #f2f2f2; }
    </style>
`;

        const users = "<p>Nama Penyetak: {{auth()->user()->name}}</p>";
        const jenislaporan = "Jenis Laporan: Laporan Booking Paket Saya";
        

        const tanggalprint = "<p>Tanggal Print: " + getFormattedDate(new Date()) + "</p>";

        let tableHTML = style + users+ jenislaporan  + tanggalprint + table.outerHTML  ;
        newWindow.document.write(tableHTML);
        newWindow.document.close();
        newWindow.print();
    }
    function getFormattedDate(date) {
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        return date.toLocaleDateString('id-ID', options);
    }
</script>

<script>
    function download() {
        const table = document.getElementById('example1'); 
        const rows = table.querySelectorAll('tbody tr');
        const csvData = [];
    
        for (const row of rows) {
            const rowData = [];
            const columns = row.querySelectorAll('td');
            for (const column of columns) {
                rowData.push(column.innerText);
            }
            csvData.push(rowData.join(','));
        }
    
        const csvContent = 'data:text/csv;charset=utf-8,' + csvData.join('\n');
        const encodedUri = encodeURI(csvContent);
        const link = document.createElement('a');
        link.setAttribute('href', encodedUri);
        link.setAttribute('download', 'filtered_data.csv');
        document.body.appendChild(link);
        link.click();
    }
    
    </script>
    <script>
        $(function() {
            let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

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
