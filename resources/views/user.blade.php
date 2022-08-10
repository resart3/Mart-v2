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
                                <td class="d-flex justify-content-center">
                                    <div class="mr-1">
                                        <a href="#" class="btn btn-primary"
                                            id='editUser' 
                                            data-id="{{$data->id}}" data-toggle="modal"
                                            data-target="#editModal">
                                            Edit
                                        </a>
                                    </div>
                                    <div>
                                        <form id="delete-form-{{$data->id}}" + action="{{ route('user.destroy', $data->id)}}"
                                            method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-danger delete">Hapus</button>
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

<!-- Modal Add User -->
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
                            <label class="col-sm-2 col-form-label" for="name">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input id="name" type="text" name="name" class="form-control @error ('name') is-invalid @enderror" value="{{old('name')}}">
                                @error ('name')
                                    <di class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input id="email" type="text" name="email" class="form-control" value="{{old('email')}}" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">NIK</label>
                            <div class="col-sm-10">
                                <input id="nik" type="text" name="nik" class="form-control" value="{{old('nik')}}">
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="role" name="role" required>
                                    <option value=""></option>
                                    <option value="user">user</option>
                                    <option value="superuser">superuser</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                  <input type="password" class="form-control" id="password" name="password" data-toggle="password" required>
                                  <div class="input-group-append">
                                    <div class="input-group-text"><i class="fa fa-eye"></i></div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Konfirmasi Password</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                  <input type="password" class="form-control" id="confirm_password" name="confirm_password" data-toggle="password" required>
                                  <div class="input-group-append">
                                    <div class="input-group-text"><i class="fa fa-eye"></i></div>
                                  </div>
                                </div>
                            </div>
                        </div>
                        
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id='AddSubmit'>Simpan</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End Modal Add User -->

<!-- Modal Edit User -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">    
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Data User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="padding-bottom: 5px">
                    <form action="">
                        <input type="hidden" id="edit_id" />
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Nama Lengkap</label>
                            <div class="col-sm-10">
                                <input id="edit_name" type="text" name="name" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <input id="edit_email" type="text" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">NIK</label>
                            <div class="col-sm-10">
                                <input id="edit_nik" type="text" name="nik" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Role</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="edit_role" name="role" required>
                                    <option value=""></option>
                                    <option value="user">user</option>
                                    <option value="superuser">superuser</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Password</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input type="password" class="form-control" id="edit_password" name="password" data-toggle="password">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="fa fa-eye"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnUpdateUser">Update Data</button>
                </div>
            </div>
        </div>
</div>
<!-- End Modal Edit User -->

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        // $(document).on('click', '.editbtn', function (e) {
        //     e.preventDefault();
        //     var id = $(this).val();
        //     // alert(stud_id);
        //     $('#editModal').modal('show');
        //     $.ajax({
        //         type: "GET",
        //         url: "/dashboard/user/edit-user/" + id,
        //         success: function (response) {
        //             if (response.status == 404) {
        //                 $('#success_message').addClass('alert alert-success');
        //                 $('#success_message').text(response.message);
        //                 $('#editModal').modal('hide');
        //             } else {
        //                 // console.log(response.student.name);
        //                 $('#name').val(response.user.name);
        //                 $('#email').val(response.user.email);
        //                 $('#nik').val(response.user.nik);
        //                 $('#role').val(response.user.role);
        //                 $('#password').val(response.user.password);
        //                 // $('#stud_id').val(stud_id);
        //             }
        //         }
        //     });
        //     $('.close').find('input').val('');

        // });

        $('[data-toggle="password"]').each(function () {
            var input = $(this);
            var eye_btn = $(this).parent().find('.input-group-text');
            eye_btn.css('cursor', 'pointer').addClass('input-password-hide');
            eye_btn.on('click', function () {
                if (eye_btn.hasClass('input-password-hide')) {
                    eye_btn.removeClass('input-password-hide').addClass('input-password-show');
                    eye_btn.find('.fa').removeClass('fa-eye').addClass('fa-eye-slash')
                    input.attr('type', 'text');
                } else {
                    eye_btn.removeClass('input-password-show').addClass('input-password-hide');
                    eye_btn.find('.fa').removeClass('fa-eye-slash').addClass('fa-eye')
                    input.attr('type', 'password');
                }
            });
        });

        $(document).on('click', '#editUser', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            $('#editModal').modal('show');
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
                url: "/dashboard/user/" + id + "/edit",
                type: "GET",
                success: function (response) {                
                    $('#edit_id').val(id);
                    $('#edit_name').val(response.name);
                    $('#edit_email').val(response.email);
                    $('#edit_nik').val(response.nik);
                    $('#edit_role').val(response.role);
                    $('#edit_password').val(response.password);
                }
            });
            $('.close').find('input').val('');
        });

        $("#btnUpdateUser").click(() => {
            const id = $("#edit_id").val();
            const name = $("#edit_name").val();
            const email = $("#edit_email").val();  
            const nik = $("#edit_nik").val();  
            const role = $("#edit_role").val();  
            const password = $("#edit_password").val(); 

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
                url: "/dashboard/user/" + id,
                type: "PUT",
                data: {
                    name: name,
                    email: email,
                    nik: nik,
                    role: role,
                    password: password
                },
                success: function (response) {
                    $('#editModal').modal('hide');
                    $('#success_message').addClass('alert alert-success');
                    $('#success_message').text("Data User Berhasil Di Update!");
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
            });
        });

        $('.AddSubmit').click(function(e){
            e.preventDefault();
            $.ajaxSetup({
                headers: {'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')},
            });
            $.ajax({
                url: `/dashboard/user`,
                method:'post',
                data:{
                    name: $('name').val(),
                    email: $('email').val(),
                    nik: $('nik').val(),
                    role: $('role').val(),
                    password: $('password').val(),
                    
                },
                success: function(result){
                    if(result.errors)
                    {
                        $('.alert-danger').html('');
                        $.each(result.errors, function(key, value){
                            $('.alert-danger').show();
                            $('.alert-danger').append('<li>'+value+'</li>');
                            
                        });
                    }else{
                        $('.alert-danger').hide();
                        $('#useraddModal').modal('hide');
                    }
                }
            });
        });
    });
</script>