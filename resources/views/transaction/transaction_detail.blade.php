@extends('layout.main')
@section('content')
    <div class="section-header">
        <div class="aligns-items-center d-inline-block">
            <h1>{{ $title }}</h1>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if ($message = Session::get('failed'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="section-body">
        <div id="success_message"></div>
        <div class="card">
            <div class="card-header d-flex flex-column align-items-start justify-content-start">
                <a href="#" class="btn btn-icon icon-left btn-primary">
                    <i class="fa fa-plus"></i>
                    &nbsp; Tambah Data Transaksi Iuran
                </a>
                <div class="dropdown mt-2">
                    <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" 
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" 
                        style="background-color: #9f1521; color: white;">
                        Pilih Tahun
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="{{ route('detail_transaction', ['nomor' => $nomor, 'tahun' => '2021']) }}">2021</a>
                        <a class="dropdown-item" href="/transaction/{{ $nomor }}/{{ 2022 }}">2022</a>
                        <a class="dropdown-item" href="{{ route('detail_transaction', ['nomor' => $nomor, 'tahun' => '2023']) }}">2023</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Jumlah</th>
                                <th>Tahun</th>
                                <th>Bulan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                            @foreach ($arrDataTransaksiSorted as $key => $data)
                                <tr class="text-center">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data["jumlah"] }}</td>
                                    <td>{{ $data["tahun"] }}</td>
                                    <td>{{ $data["bulan"] }}</td>
                                    <td>{{ $data["status"] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection



<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
