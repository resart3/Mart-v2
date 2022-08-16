@extends('layout.main')
@section('content')
<style>
    .form-section{
        padding-left: 15px;
        display: none;
    }
    .form-section.active {
        display: block;
    }

    .multi-step-form {
        overflow: hidden;
        position: relative;
    }

    .btn-info, .btn-btn-success{
        margin-top: 10px;
    }
    .parsley-error-list{
        margin: 2px 0 3px;
        padding: 0;
        list-style-type: none;
        color: red
    }
</style>

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
                <a data-target="#form-modal" href="#" class="btn btn-icon icon-left btn-primary"
                data-toggle="modal"

                >
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
                            <th style="width: 15%">Nomor KK</th>
                            <th style="width: 20%">Alamat</th>
                            <th>RT / RW</th>
                            <th>Kode Pos</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                        @foreach ($family_card as $key => $data)                            
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nomor }}</td>
                                <td style="width: 20%">{{ $data->alamat }}</td>
                                <td>{{ $data->rt_rw }}</td>
                                <td>{{ $data->kode_pos }}</td>
                                <td>
                                    <a href="data/{{ $data->nomor }}" class="btn btn-outline-primary">
                                        Detail
                                    </a>
                                    <a href="#" class="btn btn-primary" id='editCard' data-id="{{$data->nomor}}" 
                                        data-toggle="modal" data-target="#form-card-edit">
                                        Edit
                                    </a>
                                    <a
                                        href="#" data-id="{{ $data->nomor }}"
                                        data-alamat="{{ $data->alamat }}"
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

    <script>
        const multiStepForm = document.querySelector("[data-multi-step]")
        const formSteps = [...multiStepForm.querySelectorAll("[data-step]")]
        let currentStep = formSteps.findIndex(step => {
            return step.classList.contains("active")
        })
        console.log(currentStep)
        if (currentStep < 0) {
            currentStep = 0
            showCurrentStep()
            console.log(currentStep)
        }

        multiStepForm.addEventListener("click", e => {
            if (e.target.matches("[data-next]")) {
                currentStep += 1
            } else if (e.target.matches("[data-previous]")) {
                currentStep -= 1
            }

            showCurrentStep()
        })


        function showCurrentStep() {
            formSteps.forEach((step, index) => {
                step.classList.toggle("active", index === currentStep)
            })
        }
    </script>

@endsection
<!-- Modal Add Family Card -->
<div class="modal fade" id="form-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Warga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>
            <div class="modal-body" style="padding-bottom: 5px">
                <form action="{{ route('data.store') }}" method="POST">
                    @csrf
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Nomor Kartu Keluarga</label>
                            <div class="col-sm-10">
                                <input id="nomor" type="text" name="nomor" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <textarea id="alamat" name="alamat" class="form-control" style="height: 80px" oninput="handleInput(event)"></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">RT/RW</label>
                            <div class="col-sm-10">
                                <input id="rt_rw" type="text" name="rt_rw" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Kode Pos</label>
                            <div class="col-sm-10">
                                <input id="kode_pos" type="text" name="kode_pos" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Kecamatan</label>
                            <div class="col-sm-10">
                                <input id="kecamatan" type="text" name="kecamatan" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Desa / Kelurahan</label>
                            <div class="col-sm-10">
                                <input id="desa_kelurahan" type="text" name="desa_kelurahan" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Kabupaten / Kota</label>
                            <div class="col-sm-10">
                                <input id="kabupaten_kota" type="text" name="kabupaten_kota" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Provinsi</label>
                            <div class="col-sm-10">
                                <input id="provinsi" type="text" name="provinsi" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal Add Family Card -->

{{-- Modal Edit Family Card --}}
<div class="modal fade" id="form-card-edit" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Update Data Warga</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
            </div>
            <div class="modal-body" style="padding-bottom: 5px">
                <form action="">
                    <input type="hidden" id="edit_id" />
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Nomor Kartu Keluarga</label>
                            <div class="col-sm-10">
                                <input id="edit_nomor" type="text" name="nomor" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <textarea id="edit_alamat" name="alamat" class="form-control" style="height: 80px" oninput="handleInput(event)"></textarea>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">RT/RW</label>
                            <div class="col-sm-10">
                                <input id="edit_rt_rw" type="text" name="rt_rw" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Kode Pos</label>
                            <div class="col-sm-10">
                                <input id="edit_kode_pos" type="text" name="kode_pos" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Kecamatan</label>
                            <div class="col-sm-10">
                                <input id="edit_kecamatan" type="text" name="kecamatan" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Desa / Kelurahan</label>
                            <div class="col-sm-10">
                                <input id="edit_desa_kelurahan" type="text" name="desa_kelurahan" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Kabupaten / Kota</label>
                            <div class="col-sm-10">
                                <input id="edit_kabupaten_kota" type="text" name="kabupaten_kota" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Provinsi</label>
                            <div class="col-sm-10">
                                <input id="edit_provinsi" type="text" name="provinsi" class="form-control" oninput="handleInput(event)">
                            </div>
                        </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success" id="btnUpdateCard">Update Data</button>
            </div>
        </div>
    </div>
</div>
{{-- End Modal Edit Family Card --}}

@push('scripts')
        <script>
            function handleInput(e) {
                var ss = e.target.selectionStart;
                var se = e.target.selectionEnd;
                e.target.value = e.target.value.toUpperCase();
                e.target.selectionStart = ss;
                e.target.selectionEnd = se;
            }

            $(document).on('click', '#editCard', function (e) {
                e.preventDefault();
                const nomor = $("#editCard").data('id');
                $('#form-card-edit').modal('show');
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
                    url: "/dashboard/data/" + nomor + "/edit",
                    type: "GET",
                    success: function (response) {
                        $('#edit_nomor').val(response.nomor);
                        $('#edit_alamat').val(response.alamat);
                        $('#edit_rt_rw').val(response.rt_rw);
                        $('#edit_kode_pos').val(response.kode_pos);
                        $('#edit_kecamatan').val(response.kecamatan);
                        $('#edit_desa_kelurahan').val(response.desa_kelurahan);
                        $('#edit_kabupaten_kota').val(response.kabupaten_kota);
                        $('#edit_provinsi').val(response.provinsi);

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

            $("#btnUpdateCard").click(() => {
                const nomor = $('#edit_nomor').val();
                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
                    url: "/dashboard/data/" + nomor,
                    type: "PUT",
                    data: {
                        alamat: $('#edit_alamat').val(),
                        rt_rw: $('#edit_rt_rw').val(),
                        kode_pos: $('#edit_kode_pos').val(),
                        kecamatan: $('#edit_kecamatan').val(),
                        desa_kelurahan: $('#edit_desa_kelurahan').val(),
                        kabupaten_kota: $('#edit_kabupaten_kota').val(),
                        provinsi: $('#edit_provinsi').val()
                    },
                    success: function (response) {
                        $('#form-card-edit').modal('hide');
                        window.scrollTo(0, 0);
                        $('#success_message').addClass('alert alert-success');
                        $('#success_message').text("Data Family Card Berhasil Di Update!");
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                });
            });

            $("#btnUpdateMember").click(() => {
                const id = $("#edit_id").val();
                const nomor = $("#edit_nomor").val();  
                const alamat = $("#edit_alamat").val();
                const rt_rw = $("#edit_rt_rw").val();  
                const kode_pos = $("#edit_kode_pos").val();  
                const kecamatan = $("#edit_kecamatan").val();
                const desa_kelurahan = $("#edit_desa_kelurahan").val();
                const kabupaten_kota = $("#edit_kabupaten_kota").val();
                const provinsi = $("#edit_provinsi").val();
                

                $.ajax({
                    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
                    url: "/dashboard/detail/" + id,
                    type: "PUT",
                    data: {
                        nomor: nomor,
                        alamat: alamat,
                        rt_rw: rt_rw,
                        kode_pos: kode_pos,
                        kecamatan: kecamatan,
                        desa_kelurahan: desa_kelurahan,
                        kabupaten_kota: kabupaten_kota,
                        provinsi: provinsi,
                    },
                    success: function (response) {
                        console.log(response);
                        // $('#form-edit-keluarga').modal('hide');
                        // window.scrollTo(0, 0);
                        // $('#success_message').addClass('alert alert-success');
                        // $('#success_message').text("Data Family Member Berhasil Di Update!");
                        // setTimeout(() => {
                        //     location.reload();
                        // }, 1000);
                    }
                });
            });
        </script>

@endpush