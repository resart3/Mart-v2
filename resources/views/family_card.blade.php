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
                                    <a href="data/{{ $data->nomor }}/edit" class="btn btn-primary">
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
                                <textarea id="alamat" name="alamat" class="form-control" style="height: 80px" ></textarea>
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
                                <input id="kecamatan" type="text" name="kecamatan" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Desa / Kelurahan</label>
                            <div class="col-sm-10">
                                <input id="desa_kelurahan" type="text" name="desa_kelurahan" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Kabupaten / Kota</label>
                            <div class="col-sm-10">
                                <input id="kabupaten_kota" type="text" name="kabupaten_kota" class="form-control" >
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Provinsi</label>
                            <div class="col-sm-10">
                                <input id="provinsi" type="text" name="provinsi" class="form-control" >
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>