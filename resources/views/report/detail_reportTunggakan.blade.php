@extends('layout.main')
@section('content')
<style>
    .form-section{
        padding-left: 15px;
        display: none;
    }
    .form-section.active {
        display: block;
    }

    .multi-step-form {
        overflow: hidden;
        position: relative;
    }

    .btn-info, .btn-btn-success{
        margin-top: 10px;
    }
    .parsley-error-list{
        margin: 2px 0 3px;
        padding: 0;
        list-style-type: none;
        color: red
    }
</style>

    <div class="section-header">
        <div class="aligns-items-center d-inline-block">
            <h1>{{ $title }}</h1>
        </div>
    </div>
    <div class="section-body">
        <div id="success_message"></div>
        <div class="card">
            <div class="card-header d-flex justify-content-end">
                <div id="export" class="export">
                    <?php 
                        $rt_rw = $rt_rw[0]."-".$rt_rw[1];
                    ?>
                    <a href="/dashboard/report/print_DetailTunggakan/{{$rt_rw}}/{{$tahun}}/{{$bulan}}" class="btn btn-info">eksport</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>Nama Kepala Keluarga</th>
                            <th>RT RW</th>
                            <th>Jumlah Pembayaran</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                        @foreach ($detail_report as $key => $data)                            
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nomor }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->rt_rw }}</td>
                                <td>{{ $data->jumlah }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection

@push('scripts')
@endpush