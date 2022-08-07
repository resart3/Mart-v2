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
                    <!-- Table Master Tarif -->
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
                                            <a href="#tarifModalUpdate" class="edit_tarif btn btn-primary" 
                                                data-id="{{$data->id}}" data-toggle="modal" 
                                                data-target="#tarifModalUpdate">
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

                    <!-- Table Tarif K3 Warga -->
                    <div class="tab-pane" id="detailTarif" role="tabpanel" aria-labelledby="detail-tarif">
                        <a href="" class="btn btn-icon icon-left btn-primary mb-2" data-toggle="modal" data-target="#tarifWargaModal">
                            <i class="fa fa-plus"></i>
                            &nbsp; Tambah Data Tarif K3 Warga
                        </a>
                        <div class="table-responsive">
                            <table id="dataTable" class="table-bordered table-md table">
                                <thead>
                                    <tr>                                        
                                        <th>Nomor KK</th>
                                        <th>Nama</th>
                                        <th>Luas Bangunan</th>
                                        <th>Nomor Rumah</th>
                                        <th>Nominal</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody style="font-size: 14px!important">
                                @foreach ($land as $key => $data)
                                    <tr>
                                        <td> {{$key+1}} </td>
                                        <td> {{$data->nomor}} </td>
                                        <td> {{$data->nama}} </td>
                                        <td> {{$data->area}} </td>
                                        <td> {{$data->house_number}} </td>
                                        <td> {{$data->amount}} </td>
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
                                        <form id="delete-form-{{$data->id}}" + action="{{ route('land.destroy', $data->id)}}"
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
        </div>
    </div>

@endsection

    <!-- Modal Add Tarif  -->
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
    <!-- End Modal Add Tarif -->


    <!-- Modal Add Tarif Warga -->
    <div class="modal fade" id="tarifWargaModal" tabindex="-1" role="dialog" aria-hidden="true">
        <form action="/dashboard/tarif/tarif_warga" method="POST">
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
                                <label class="col-sm-2 col-form-label">Nomor KK</label>
                                <div class="col-sm-10">
                                    <input id="nomor_kk" type="text" name="nomor_kk" class="form-control" >
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Nama</label>
                                <div class="col-sm-10">
                                    <input id="name" type="text" name="name" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Kategori</label>
                                <div class="col-sm-10">                                    
                                    <select class="form-control" id="kategoriWarga" name="kategoriWarga">
                                        <option></option>
                                        @foreach ($tarif as $key => $data)
                                            <option value="{{$data->id}}">{{$data->category_name}}</option>     
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Nominal Tarif</label>
                                <div class="col-sm-10">
                                    <input id="nominalWarga" type="text" name="nominalWarga" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Luas Kavling</label>
                                <div class="col-sm-10">
                                    <input id="luasTanah" type="text" name="luasTanah" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group row mb-4">
                                <label class="col-sm-2 col-form-label">Nomor Rumah</label>
                                <div class="col-sm-10">
                                    <input id="nomorRumah" type="text" name="nomorRumah" class="form-control" required>
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
    </div>
    <!-- End Modal Add Tarif Warga -->
    
    <!-- Modal Update Tarif -->
    <div class="modal fade" id="tarifModalUpdate" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Tarif K3</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body" style="padding-bottom: 5px">
                        <input type="hidden" id="id" />
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
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary update_tarif">Update Data</button>
                    </div>
                </div>
            </div>
    </div>
    <!-- End Modal Update Tarif -->

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    $("#nomor_kk").change(() => {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `/dashboard/tarif/nama_warga`,
            type: 'POST',
            data: {
                nomorKk : $("#nomor_kk").val(),
            },
            success: function(data) {                
                $("#name").val(data[0].nama);
            }
        });
    })

    $("#kategoriWarga").change(() => {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: `/dashboard/tarif/category_amount`,
            type: 'POST',
            data: {
                category_id : $("#kategoriWarga").val(),
            },
            success: function(data) {
                $("#nominalWarga").val(data[0].amount)
            }
        });
    })

    $(document).on('click', '.edit_tarif', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        console.log(id);
        $('#tarifModalUpdate').modal('show');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: "GET",
            url: "/dashboard/tarif/edit-tarif/" + id,
            success: function (response) {
                if (response.status == 404) {
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text(response.message);
                    $('#tarifModalUpdate').modal('hide');
                } else {
                    // console.log(response.land.category_name);
                    $('#id').val(id);
                    $('#category_name').val(response.tarif.category_name);
                    $('#amount').val(response.tarif.amount);
                }
            }
        });
        $('.close').find('input').val('');

    });

    // $(document).on('click', '.update_tarif', function (e) {
    //         e.preventDefault();

    //         $(this).text('Updating..');
    //         var id = $('#id').val();
    //         // alert(id);

    //         var data = {
    //             'name': $('#name').val(),
    //             'course': $('#course').val(),
    //             'email': $('#email').val(),
    //             'phone': $('#phone').val(),
    //         }

    //         $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });

    //         $.ajax({
    //             type: "PUT",
    //             url: "/update-student/" + id,
    //             data: data,
    //             dataType: "json",
    //             success: function (response) {
    //                 // console.log(response);
    //                 if (response.status == 400) {
    //                     $('#update_msgList').html("");
    //                     $('#update_msgList').addClass('alert alert-danger');
    //                     $.each(response.errors, function (key, err_value) {
    //                         $('#update_msgList').append('<li>' + err_value +
    //                             '</li>');
    //                     });
    //                     $('.update_student').text('Update');
    //                 } else {
    //                     $('#update_msgList').html("");

    //                     $('#success_message').addClass('alert alert-success');
    //                     $('#success_message').text(response.message);
    //                     $('#editModal').find('input').val('');
    //                     $('.update_student').text('Update');
    //                     $('#editModal').modal('hide');
    //                     fetchstudent();
    //                 }
    //             }
    //         });

    //     });
</script>