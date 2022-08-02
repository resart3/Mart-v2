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
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <a href="{{ url('dashboard/data') }}" class="btn btn-icon icon-left btn-primary">
                    <i class="fa fa-plus"></i>
                    &nbsp; Tambah Data Iuran
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 20%">Bukti Bayar</th>
                            <th>ID Kartu Keluarga</th>
                            <th>Bulan </th>
                            <th>Tahun</th>
                            <th style="width: 20%">Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                        @foreach ($transactions as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                @if($data->bukti)
                                    <td>{{ $data->bukti }}</td>
                                @else
                                    <td>Bukti Bayar Belum Tersedia</td>
                                @endif
                                <td>{{ $data->family_card_id }}</td>
                                <td>{{ $data->bulan }}</td>
                                <td>{{ $data->tahun }}</td>
                                <td>{{ $data->status}}</td>
                                <td>
                                    <a href="data/{{ $data->id }}/edit" class="btn btn-primary">
                                        Edit
                                    </a>
                                    <a
                                        href="{{ route('transaction.index') }}" 
                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{$data->id}}').submit();" 
                                        class="btn btn-danger delete">Hapus
                                    </a>
                                </td>
                                <form id="delete-form-{{$data->id}}" + action="{{ route('transaction.destroy', $data->id)}}"
                                    method="POST">
                                    @csrf @method('DELETE')
                                </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
