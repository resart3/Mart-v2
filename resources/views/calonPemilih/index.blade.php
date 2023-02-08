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
                @if (session()->get('user')->role != 'admin_rt')
                    <div class="form-group m-0">
                        <select id="rt_filter" class="form-control" aria-label="Default select example">
                            <option disabled selected value="">Silahkan pilih nomor RT</option>
                            @foreach ($rt as $key => $data)
                                    <option value={{ explode("/",$data->rt_rw)[0] }}>{{ explode("/",$data->rt_rw)[0] }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable_calon" class="table-bordered table-md table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Usia</th>
                            <th>Alamat</th>
                            <th>RT/RW</th>
                            <th>Kode Pos</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                        @foreach ($calon_data as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->age }}</td>
                                <td>{{ $data->alamat }}</td>
                                <td>{{ $data->rt_rw }}</td>
                                <td>{{ $data->kode_pos }}</td>
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
{{-- <div class="modal fade" id="form-add-keluarga" tabindex="-1" role="dialog" aria-hidden="true">
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
</div> --}}
<!-- End Modal Add Member -->

{{-- Modal Detail Member --}}
{{-- <div class="modal fade" id="detailModal" tabindex="-1" role="dialog" aria-hidden="true">
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
</div> --}}
{{-- End Modal Detail Member --}}

<!-- Modal Edit Member -->
{{-- <div class="modal fade" id="form-edit-keluarga" tabindex="-1" role="dialog" aria-hidden="true">
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
</div> --}}
<!-- End Modal Edit Member -->

@push('scripts')
<script>

$(document).ready(function() {
    let table_calon = $("#dataTable_calon").DataTable({
        info: false,
        responsive: true,
    })

    $("#rt_filter").change(function(){
        let rt = $(this).val();
        table_calon.clear().draw();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
            url: "/dashboard/calonPemilih/filter/" + rt,
            type: "GET",
            success: function (response) {
                let no = 1;
                $.each(response,function() {
                    table_calon.row.add(
                        [no++, this.nama,this.age ,this.alamat, this.rt_rw, this.kode_pos]
                    ).draw(false);
                })
            },
            error: function (response){
                console.log(response);
            }
        });
    })
})

</script>
@endpush
