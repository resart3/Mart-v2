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
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tarif" data-toggle="tab" href="#homeTarif" role="tab" aria-controls="home" aria-selected="true">Master Tarif</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="detail-tarif" data-toggle="tab" href="#detailTarif" role="tab" aria-controls="profile" aria-selected="false">Tambah Tarif Warga</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane active" id="homeTarif" role="tabpanel" aria-labelledby="home-tarif">
                        <a href="#tarifModal" class="btn btn-icon icon-left btn-primary" data-toggle="modal" data-target="#tarifModal">
                            <i class="fa fa-plus"></i>
                            &nbsp; Tambah Data Tarif K3
                        </a>
                        <div class="table-responsive">
                            <table id="dataTable" class="table-bordered table-md table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th style="width: 50%">Kategori</th>
                                    <th>Nominal</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody style="font-size: 14px!important">
                                @foreach ($tarif as $key => $data)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $data->category_name }}</td>
                                        <td>{{ $data->amount }}</td>
                                        <td>
                                            <a href="" class="btn btn-primary">
                                                Edit
                                            </a>
                                            <a
                                                href="{{ route('tarif.index') }}" 
                                                onclick="event.preventDefault(); document.getElementById('delete-form-{{$data->id}}').submit();" 
                                                class="btn btn-danger delete">Hapus
                                            </a>
                                        </td>
                                        <form id="delete-form-{{$data->id}}" + action="{{ route('tarif.destroy', $data->id)}}"
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
                    <div class="tab-pane" id="detailTarif" role="tabpanel" aria-labelledby="detail-tarif">
                        <a href="" class="btn btn-icon icon-left btn-primary" data-toggle="modal" data-target="#tarifWargaModal">
                            <i class="fa fa-plus"></i>
                            &nbsp; Tambah Data Tarif K3 Warga
                        </a>
                        <div class="table-responsive">
                            <table id="dataTable" class="table-bordered table-md table">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Luas Bangunan</th>
                                    <th>Nomor Rumah</th>
                                    <th>Nominal</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody style="font-size: 14px!important">
                                @foreach ($tarif as $key => $data)
                                    <tr>
                                        
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

    <div class="modal fade" id="tarifModal" tabindex="-1" role="dialog" aria-hidden="true">
        <form action="{{ route('tarif.store') }}" method="POST">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Tarif K3</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-bottom: 5px">
                        <form action="" >
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Kategori</label>
                                <div class="col-sm-10">
                                    <input id="category_name" type="text" name="category_name" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Nominal Tarif</label>
                                <div class="col-sm-10">
                                    <input id="amount" type="text" name="amount" class="form-control" >
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="modal fade" id="tarifWargaModal" tabindex="-1" role="dialog" aria-hidden="true">
        <form action="" >
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Tarif K3 Warga</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-bottom: 5px">
                        <form action="" >
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input id="name" type="text" name="name" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">NIK</label>
                                <div class="col-sm-10">
                                    <input id="nominal" type="text" name="nominal" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Kategori</label>
                                <div class="col-sm-10">
                                    <input id="kategori" type="text" name="kategori" class="form-control" placeholder="cth: Kategori 1">
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Detail Tarif</label>
                                <div class="col-sm-10">
                                    <textarea id="detail" name="detail" class="form-control" style="height: 80px" ></textarea>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Nominal Tarif</label>
                                <div class="col-sm-10">
                                    <input id="nominal" type="text" name="nominal" class="form-control" >
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </form>
    </div>