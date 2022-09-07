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
                <a data-target="#form-add-keluarga" href="#" class="btn btn-icon icon-left btn-primary"
                data-toggle="modal">
                    <i class="fa fa-plus"></i>
                    &nbsp; Tambah Data Anggota Keluarga
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
                                <td class="d-flex justify-content-center">
                                    <div class="mr-1">
                                        <a href="#" data-id="{{ $data->id }}" 
                                            class="detail btn btn-outline-primary" id="detailMember" 
                                            data-toggle="modal" data-target="#detailModal">
                                            Detail
                                        </a>
                                    </div>
                                    <div class="mr-1">
                                        <a href="#" class="btn btn-primary" id='editMember' 
                                            data-id="{{$data->id}}" data-toggle="modal"
                                            data-target="#form-edit-keluarga" onclick="(function(){
                                                $('#alert_nik').addClass('d-none');
                                            })()">
                                            Edit
                                        </a>
                                    </div>
                                    <div>
                                        <form action="{{ route('detail.destroy', $data->id)}}" 
                                            method="POST">
                                            @csrf @method('DELETE')
                                            <input type="hidden" value="{{ $data->family_card_id }}" name="nomor"/>
                                            <button class="btn btn-danger delete" 
                                                onclick="return confirm('Apakah anda yakin hapus?');">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
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
                                <input id="nama" type="text" name="nama" class="form-control" 
                                    oninput="handleInput(event)" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">NIK</label>
                            <div class="col-sm-10">
                                <input id="nik" type="text" name="nik" class="form-control" 
                                    onkeypress="disableSpacingAndLetter(event)" required>
                                <input id="no_kk" type="text" name="nomor" class="d-none form-control" 
                                    value="{{ $id }}" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-10">
                                <input id="tempat_lahir" type="text" name="tempat_lahir" class="form-control" 
                                    oninput="handleInput(event)" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input id="tanggal_lahir" type="date" name="tanggal_lahir" 
                                    class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="jenis_kelamin" id="jenis_kelamin" 
                                    required>
                                    <option value=""></option>
                                    <option value="Laki - Laki">Laki-Laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Agama</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="agama" id="agama" required>
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
                                <input id="pendidikan" type="text" name="pendidikan" class="form-control" 
                                    oninput="handleInput(event)" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Pekerjaan</label>
                            <div class="col-sm-10">
                                <input id="pekerjaan" type="text" name="pekerjaan" class="form-control" 
                                    oninput="handleInput(event)" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Golongan Darah</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="golongan_darah" id="golongan_darah" 
                                    required>
                                    <option value=""></option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="AB">AB</option>
                                    <option value="O">O</option>
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
                            <input id="edit_nama" type="text" name="nama" class="form-control" 
                                placeholder="Nama" oninput="handleInput(event)" required>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">NIK</label>
                        <div class="col-sm-10">
                            <input id="edit_nik" type="text" name="nik" class="form-control" 
                                onkeypress="disableSpacingAndLetter(event)" required>
                            <p class="text-danger mb-0 mt-1 d-none" id="alert_nik">NIK telah terdaftar!</p>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                        <div class="col-sm-10">
                            <input id="edit_tempat_lahir" type="text" name="tempat_lahir" class="form-control" 
                                oninput="handleInput(event)" required>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                        <div class="col-sm-10">
                            <input id="edit_tanggal_lahir" type="date" name="tanggal_lahir" class="form-control" 
                                required>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Jenis Kelamin</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="jenis_kelamin" id="edit_jenis_kelamin" 
                                required>
                                <option value=""></option>
                                <option value="Laki - Laki">Laki-Laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Agama</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="agama" id="edit_agama" required>
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
                            <input id="edit_pendidikan" type="text" name="pendidikan" class="form-control" 
                                oninput="handleInput(event)" required>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Pekerjaan</label>
                        <div class="col-sm-10">
                            <input id="edit_pekerjaan" type="text" name="pekerjaan" class="form-control" 
                                oninput="handleInput(event)" required>
                        </div>
                    </div>
                    <div class="form-group row mb-4">
                        <label class="col-sm-2 col-form-label">Golongan Darah</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="golongan_darah" id="edit_golongan_darah" 
                                required>
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
                            <select class="form-control" name="isFamilyHead" id="edit_isFamilyHead" 
                                required>
                                <option value=""></option>
                                <option value="1">Iya</option>
                                <option value="0">Tidak</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" id="btnUpdateMember">Update Data</button>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Edit Member -->

@push('scripts')
    <script>
        function disableSpacingAndLetter(e) {
            if(e.which === 32){
                e.preventDefault();
            }else{
                let charCode = (e.which) ? e.which : event.keyCode;
                if (String.fromCharCode(charCode).match(/[^0-9]/g))
                    e.preventDefault();
            }
        }

        function handleInput(e) {
            var ss = e.target.selectionStart;
            var se = e.target.selectionEnd;
            e.target.value = e.target.value.toUpperCase();
            e.target.selectionStart = ss;
            e.target.selectionEnd = se;
        }

        $(document).on('click', '#detailMember', function (e) {
            const id = $(this).data('id');
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
                    if(response == "Success"){
                        $('#form-edit-keluarga').modal('hide');
                        window.scrollTo(0, 0);
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text("Data Family Member Berhasil Di Update!");
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }else{
                        $("#alert_nik").removeClass('d-none');
                    }
                    
                }
            });
        });
    </script>
@endpush
