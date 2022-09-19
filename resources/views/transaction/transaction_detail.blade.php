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
            <div class="card-header d-flex justify-content-between">
                <a href="#" class="btn btn-icon icon-left btn-primary">
                    <i class="fa fa-plus"></i>
                    &nbsp; Tambah Data Transaksi Iuran
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Nomor KK</th>
                                <th>Nama Kepala Keluarga</th>
                                <th>Action</th>
                            </tr>
                        {{-- </thead>
                        <tbody style="font-size: 14px!important">
                            @foreach ($family_card as $key => $data)
                                <tr class="text-center">
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->nomor }}</td>
                                    <td>
                                        @foreach ($data->with_family_head as $head )
                                            {{ $head->nama }}
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-primary"> Detail </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody> --}}
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection



<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
