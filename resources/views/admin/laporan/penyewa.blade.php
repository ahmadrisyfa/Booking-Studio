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
                <form action="{{url('admin/laporan_penyewa/search')}}" method="post">
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
                    <div class="col-md-12" style="margin-top: 18px;">        
                                <button class="btn btn-info" style="font-weight:bold" type="submit"><i class="fa fa-search" style="margin-right:8px"></i>Cari</button>
                                <button type="button" class="btn btn-success" onclick="cetaktable()">Cetak</button>
                                <button type="button" class="btn btn-info" onclick="download()">Download</button>
                                <button type="button" class="btn btn-warning" onclick="downloadAsPdf()">Download Pdf</button>                            
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped " cellspacing="0"
                        width="100%">
                        <thead>
                            <tr>
                               
                                <th>No</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Tanggal Di Buat</th>
                                <th>Status</th>                            
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($user as $value)
                                <tr data-entry-id="{{ $value->id }}">                                   
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $value->name }}</td>
                                    <td>{{ $value->email }}</td>
                                    <td>{{ Carbon\Carbon::parse($value->created_at)->format('M, d D H:i:s') }}</td>
                                    <td>
                                        <span class="btn btn-success btn-sm">Aktif</span>
                                    </td>
                                    {{-- <td>
                                        <a href="#" class="btn btn-info btn-sm">Edit</a>
                                        <a href="#" class="btn btn-primary btn-sm">Hapus</a>

                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">{{ __('Data Empty') }}</td>
                                </tr>
                            @endforelse
                            <tr>
                                <td>Jumlah</td>
                                <td colspan="6">{{$jumlah}}</td>
                            </tr>
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
        function cetaktable() {
            const table = document.getElementById('example1'); 
            const newWindow = window.open('', '_blank');
            const style = `
                <style>
                    body { }
                    table { border-collapse: collapse; width: 100%; }
                    th, td { border: 1px solid black; padding: 8px; }
                    th { background-color: #f2f2f2; }
                    h2 {text-align: center;  }
                    h4 {text-align: center;  }
                    h5 {text-align: right;  }
                </style>
            `;
            const users = "<h5>{{auth()->user()->name}}</h5>";
            const nama = "<h2>Matrix Music Studio</h2>";
            const jenislaporan = "<h2> Laporan Penyewa</h2>";      

            const tanggalPeriode = "<h4>{{ request('fromDate') }} s/d {{ request('toDate') }}</h4><br><br><br><br>"
            const tanggalprint = "<br><br><h5>" + getFormattedDate(new Date()) + "</h5>";

            let tableHTML = style + nama + jenislaporan + tanggalPeriode  + table.outerHTML  + users + tanggalprint ;
            newWindow.document.write(tableHTML);
            newWindow.document.close();
            newWindow.print();
        }
        function getFormattedDate(date) {
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            return date.toLocaleDateString('id-ID', options);
        }
    </script>
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
            $('.datatable-booking:not(.ajaxTable)').DataTable({
                buttons: dtButtons
            })
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                $($.fn.dataTable.tables(true)).DataTable()
                    .columns.adjust();
            });
        })
    </>
@endpush
