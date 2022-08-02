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
                <a href="#useraddModal" class="btn btn-icon icon-left btn-primary" data-toggle="modal" data-target="#useraddModal">
                    <i class="fa fa-plus"></i>
                    &nbsp; Tambah Data User
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Tanggal Dibuat</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($user as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->role }}</td>
                                <td>{{ $data->created_at }}</td>
                                <td>
                                    <a href="museum/{{ $data->id }}/edit" class="btn btn-primary">
                                        Edit
                                    </a>
                                    <a
                                        href="{{ route('user.index') }}" 
                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{$data->id}}').submit();" 
                                        class="btn btn-danger delete">Hapus
                                    </a>
                                </td>
                                <form id="delete-form-{{$data->id}}" + action="{{ route('user.destroy', $data->id)}}"
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

    <div class="modal fade" id="useraddModal" tabindex="-1" role="dialog" aria-hidden="true">
        <form action="{{ route('user.store') }}" method="POST">
            @csrf
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tambah Data User</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-bottom: 5px">
                        <form action="">
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Nama Lengkap</label>
                                <div class="col-sm-10">
                                    <input id="name" type="text" name="name" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Email</label>
                                <div class="col-sm-10">
                                    <input id="email" type="text" name="email" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">NIK</label>
                                <div class="col-sm-10">
                                    <input id="nik" type="text" name="nik" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Role</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="role" name="role">
                                        <option value=""></option>
                                        <option value="user">user</option>
                                        <option value="superuser">superuser</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Password</label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" id="password" name="password">
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