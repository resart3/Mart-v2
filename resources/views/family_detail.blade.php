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
                                    <a href="#" data-id="{{ $data->id }}" 
                                        class="detail btn btn-outline-primary" id="detailMember" 
                                        data-toggle="modal" data-target="#detailModal">
                                        Detail
                                    </a>
                                    <a href="#" class="btn btn-primary"
                                        id='editMember' 
                                        data-id="{{$data->id}}" data-toggle="modal"
                                        data-target="#form-edit-keluarga">
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

<!-- Modal Add Member -->
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
                                <input id="nama" type="text" name="nama" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">NIK</label>
                            <div class="col-sm-10">
                                <input id="nik" type="text" name="nik" class="form-control" >
                                <input id="nik" type="text" name="nomor" class="d-none form-control" value="{{ $id }}" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-10">
                                <input id="tempat_lahir" type="text" name="tempat_lahir" class="form-control" oninput="handleInput(event)">
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
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin">
                                    <option value=""></option>
                                    <option value="Laki - Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Agama</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="agama" id="agama">
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
                                <input id="pendidikan" type="text" name="pendidikan" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Pekerjaan</label>
                            <div class="col-sm-10">
                                <input id="pekerjaan" type="text" name="pekerjaan" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Golongan Darah</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="golongan_darah" id="golongan_darah">
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
                                <select class="form-control" name="isFamilyHead" id="IsFamilyHead">
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
<!-- End Modal Add Member -->

{{-- Modal Detail Member --}}
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detail Anggota Keluarga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="padding-bottom: 5px">
                <form action="">
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input id="detail_nama" type="text" name="nama" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <input id="detail_nik" type="text" name="nik" class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-10">
                            <input id="detail_tempat_lahir" type="text" name="tempat_lahir" 
                                class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input id="detail_tanggal_lahir" type="text" name="tanggal_lahir" 
                                class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10">
                            <input id="detail_jenis_kelamin" type="text" name="jenis_kelamin" 
                                class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Agama</label>
                        <div class="col-sm-10">
                            <input id="detail_agama" type="text" name="agama" 
                                class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Pendidikan Terakhir</label>
                        <div class="col-sm-10">
                            <input id="detail_pendidikan" type="text" name="pendidikan" 
                                class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Pekerjaan</label>
                        <div class="col-sm-10">
                            <input id="detail_pekerjaan" type="text" name="pekerjaan" 
                                class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Golongan Darah</label>
                        <div class="col-sm-10">
                            <input id="detail_golongan_darah" type="text" name="golongan_darah" 
                                class="form-control" readonly>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Kepala Keluarga?</label>
                        <div class="col-sm-10">
                            <input id="detail_isFamilyHead" type="text" name="isFamilyHead" 
                                class="form-control" readonly>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
{{-- End Modal Detail Member --}}

<!-- Modal Edit Member -->
<div class="modal fade" id="form-edit-keluarga" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Anggota Keluarga</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="padding-bottom: 5px">
                <form action="">
                    <input type="hidden" id="edit_id" />
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Nama</label>
                        <div class="col-sm-10">
                            <input id="edit_nama" type="text" name="nama" class="form-control" placeholder="" oninput="handleInput(event)">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <input id="edit_nik" type="text" name="nik" class="form-control" >
                            <input id="nik" type="text" name="nomor" class="d-none form-control" value="{{ $id }}" >
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-10">
                            <input id="edit_tempat_lahir" type="text" name="tempat_lahir" class="form-control" oninput="handleInput(event)">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input id="edit_tanggal_lahir" type="date" name="tanggal_lahir" class="form-control" >
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="jenis_kelamin" id="edit_jenis_kelamin">
                                <option value=""></option>
                                <option value="Laki - Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Agama</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="agama" id="edit_agama">
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
                            <input id="edit_pendidikan" type="text" name="pendidikan" class="form-control" oninput="handleInput(event)">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Pekerjaan</label>
                        <div class="col-sm-10">
                            <input id="edit_pekerjaan" type="text" name="pekerjaan" class="form-control" oninput="handleInput(event)">
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Golongan Darah</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="golongan_darah" id="edit_golongan_darah">
                                <option value=""></option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                                <option value="TIDAK TAHU">TIDAK TAHU</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Kepala Keluarga?</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="isFamilyHead" id="edit_isFamilyHead">
                                <option value=""></option>
                                <option value="1">Iya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnUpdateMember">Simpan</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Edit Member -->

@push('scripts')
    <script>
        // $(document).ready(function() {
        //     $(document).on('click', '.detail', function(event) {
        //         event.preventDefault();
        //         const id = $(this).data('id');
        //         $.ajax({
        //             type: 'GET',
        //             url: `{{ url('api/data') }}/${id}`,
        //             success: (res) => {
        //                 $('#titleModal').html('Detail Kartu Keluarga')
        //                 $('#nomor').val(res.data.nomor);
        //                 $('#alamat').val(res.data.alamat);
        //                 $('#rt_rw').val(res.data.rt_rw);
        //                 $('#kode_pos').val(res.data.kode_pos);
        //                 $('#kecamatan').val(res.data.kecamatan);
        //                 $('#kabupaten_kota').val(res.data.kabupaten_kota);
        //                 $('#provinsi').val(res.data.provinsi);
        //                 $('#detailModal').modal('show');
        //             },
        //             error: function(data) {
        //                 console.log(data);
        //             }
        //         });
        //     });
        // });

        function handleInput(e) {
            var ss = e.target.selectionStart;
            var se = e.target.selectionEnd;
            e.target.value = e.target.value.toUpperCase();
            e.target.selectionStart = ss;
            e.target.selectionEnd = se;
        }

        $(document).on('click', '#detailMember', function (e) {
            const id = $(this).data('id');
            console.log(id);
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
                url: "/dashboard/detail/" + id + "/edit",
                type: "GET",
                success: function (response) {
                    $('#detail_id').val(id);
                    $('#detail_nama').val(response.nama);
                    $('#detail_nik').val(response.nik);
                    $('#detail_tempat_lahir').val(response.tempat_lahir);

                    const dateSplitted = response.tanggal_lahir.split("-");
                    const tanggal_lahir = `${dateSplitted[2]}-${dateSplitted[1]}-${dateSplitted[0]}`;
                    $("#detail_tanggal_lahir").val(tanggal_lahir);

                    $('#detail_jenis_kelamin').val(response.jenis_kelamin);
                    $('#detail_agama').val(response.agama);
                    $('#detail_pendidikan').val(response.pendidikan);
                    $('#detail_pekerjaan').val(response.pekerjaan);
                    $('#detail_golongan_darah').val(response.golongan_darah);

                    if(response.isFamilyHead == 0){
                        $('#detail_isFamilyHead').val("Tidak");
                    }else{
                        $('#detail_isFamilyHead').val("Iya");
                    }
                }
            });
        });

        $(document).on('click', '#editMember', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            $('#form-edit-keluarga').modal('show');
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
                url: "/dashboard/detail/" + id + "/edit",
                type: "GET",
                success: function (response) {
                    $('#edit_id').val(id);
                    $('#edit_nama').val(response.nama);
                    $('#edit_nik').val(response.nik);
                    $('#edit_tempat_lahir').val(response.tempat_lahir);
                    $('#edit_jenis_kelamin').val(response.jenis_kelamin);
                    $('#edit_agama').val(response.agama);
                    $('#edit_pendidikan').val(response.pendidikan);
                    $('#edit_pekerjaan').val(response.pekerjaan);
                    $('#edit_golongan_darah').val(response.golongan_darah);
                    $('#edit_isFamilyHead').val(response.isFamilyHead);

                    if(response.rt_rw){
                        $("#edit_rt").parent().parent().prop("hidden", false);
                        $("#edit_rw").parent().parent().prop("hidden", false);

                        let temp = response.rt_rw.split("/");
                        $('#edit_rt').val(temp[0]);
                        $('#edit_rw').val(temp[1]);
                    }
                }
            });
            $('.close').find('input').val('');
        });

        $("#btnUpdateMember").click(() => {
            const id = $('#edit_id').val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
                url: "/dashboard/detail/" + id,
                type: "PUT",
                data: {
                    nama: $('#edit_nama').val(),
                    nik: $('#edit_nik').val(),
                    tempat_lahir: $('#edit_tempat_lahir').val(),
                    jenis_kelamin: $('#edit_jenis_kelamin').val(),
                    agama: $('#edit_agama').val(),
                    pendidikan: $('#edit_pendidikan').val(),
                    pekerjaan: $('#edit_pekerjaan').val(),
                    golongan_darah: $('#edit_golongan_darah').val(),
                    isFamilyHead: $('#edit_isFamilyHead').val(),
                },
                success: function (response) {
                    $('#form-edit-keluarga').modal('hide');
                    window.scrollTo(0, 0);
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text("Data Family Member Berhasil Di Update!");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            });
        });
    </script>
@endpush
