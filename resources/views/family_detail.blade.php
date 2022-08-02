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
                <a data-target="#form-add-keluarga" href="#" class="btn btn-icon icon-left btn-primary"
                data-toggle="modal">
                    <i class="fa fa-plus"></i>
                    &nbsp; Tambah Data Anggota Warga
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Tempat Lahir</th>
                            <th>Tanggal Lahir</th>
                            <th>Jenis Kelamin</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                        @foreach ($family_member as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nik }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->tempat_lahir }}</td>
                                <td>{{ date('d F Y', strtotime($data->tanggal_lahir  )) }}</td>
                                <td>{{ $data->jenis_kelamin }}</td>
                                <td>
                                    <a href="#"
                                       data-id="{{ $data->family_card_id }}"
                                       class="detail btn btn-outline-primary"
                                       data-toggle="modal"
                                       data-target="#detailModal"
                                    >
                                        Detail
                                    </a>
                                    <a href="data/{{ $data->id }}/edit" class="btn btn-primary">
                                        Edit
                                    </a>
                                    <a
                                        href="{{ route('data.index') }}" 
                                        onclick="event.preventDefault(); document.getElementById('delete-form-{{$data->id}}').submit();" 
                                        class="btn btn-danger delete">Hapus
                                    </a>
                                </td>
                                <form id="delete-form-{{$data->id}}" + action="{{ route('data.destroy', $data->id)}}"
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


<div class="modal fade" id="form-add-keluarga" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="{{route('detail.store')}}" method="POST">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Anggota Keluarga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="padding-bottom: 5px">
                    <form action="" >
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Nama</label>
                            <div class="col-sm-10">
                                <input id="nama" type="text" name="category_name" class="form-control" placeholder="">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">NIK</label>
                            <div class="col-sm-10">
                                <input id="nik" type="text" name="amount" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-10">
                                <input id="tempat_lahir" type="text" name="tempat_lahir" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input id="tanggal_lahir" type="date" name="tanggal_lahir" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="jenis_kelamin">
                                    <option value=""></option>
                                    <option value="Laki-Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Agama</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="agama">
                                    <option value=""></option>
                                    <option value="ISLAM">ISLAM</option>
                                    <option value="PROTESTAN">PROTESTAN</option>
                                    <option value="KATOLIK">KATOLIK</option>
                                    <option value="HINDU">HINDU</option>
                                    <option value="BUDDHA">BUDDHA</option>
                                    <option value="KHONGHUCU">KHONGHUCU</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Pendidikan Terakhir</label>
                            <div class="col-sm-10">
                                <input id="pendidikan" type="text" name="pendidikan" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Pekerjaan</label>
                            <div class="col-sm-10">
                                <input id="pekerjaan" type="text" name="pekerjaan" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Golongan Darah</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="golongan_darah">
                                    <option value=""></option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Kepala Keluarga?</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="IsFamilyHead">
                                    <option value=""></option>
                                    <option value="1">Iya</option>
                                    <option value="0">Tidak</option>
                                </select>
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

<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <h3 class="mt-4 mb-3 text-center" id="titleModal"></h3>
            <div class="modal-body" style="padding-bottom: 5px">
                <div class="form-group row mb-4">
                    <label class="col-sm-2 col-form-label">Nomor Kartu Keluarga</label>
                    <div class="col-sm-10">
                        <input id="nomor" type="text" name="nomor" class="form-control" disabled>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-sm-2 col-form-label">Alamat</label>
                    <div class="col-sm-10">
                        <textarea id="alamat" name="alamat" class="form-control" style="height: 80px" disabled></textarea>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-sm-2 col-form-label">rt_rw</label>
                    <div class="col-sm-10">
                        <input id="rt_rw" type="text" name="rt_rw" class="form-control" disabled>
                    </div>
                </div>

                <div class="form-group row mb-4">
                    <label class="col-sm-2 col-form-label">Harga Satuan</label>
                    <div class="col-sm-10">
                        <input id="harga" type="text" name="harga" class="form-control" disabled>
                    </div>
                </div>
                <div class="form-group row mb-4">
                    <label class="col-sm-2 col-form-label">Lokasi</label>
                    <div class="col-sm-10">
                        <div id='map' style='width: 100%; height: 200px;'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.detail', function(event) {
                event.preventDefault();
                const id = $(this).data('id');
                $.ajax({
                    type: 'GET',
                    url: `{{ url('api/data') }}/${id}`,
                    success: (res) => {
                        $('#titleModal').html('Detail Kartu Keluarga')
                        $('#nomor').val(res.data.nomor);
                        $('#alamat').val(res.data.alamat);
                        $('#rt_rw').val(res.data.rt_rw);
                        $('#kode_pos').val(res.data.kode_pos);
                        $('#kecamatan').val(res.data.kecamatan);
                        $('#kabupaten_kota').val(res.data.kabupaten_kota);
                        $('#provinsi').val(res.data.provinsi);
                        $('#detailModal').modal('show');
                    },
                    error: function(data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>
@endpush
