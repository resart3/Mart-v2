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
                <a href="{{ url('museum/create') }}" class="btn btn-icon icon-left btn-primary">
                    <i class="fa fa-plus"></i>
                    &nbsp; Tambah Data Warga
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
                                        href="#" data-id="{{ $data->id }}"
                                        class="btn btn-danger delete"
                                        data-toggle="modal"
                                        data-target="#deleteModal">Hapus
                                    </a>
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
