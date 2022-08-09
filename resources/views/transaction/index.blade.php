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
                <a href="{{ url('dashboard/data') }}" class="btn btn-icon icon-left btn-primary">
                    <i class="fa fa-plus"></i>
                    &nbsp; Tambah Data Iuran
                </a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th style="width: 20%">Bukti Bayar</th>
                            <th>ID Kartu Keluarga</th>
                            <th>Bulan </th>
                            <th>Tahun</th>
                            <th style="width: 20%">Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                        @foreach ($transactions as $key => $data)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                @if($data->receipt)
                                    <td>{{ $data->receipt }}</td>
                                @else
                                    <td>Bukti Bayar Belum Tersedia</td>
                                @endif
                                <td>{{ $data->family_card_id }}</td>
                                <td>{{ $data->bulan }}</td>
                                <td>{{ $data->tahun }}</td>
                                <td>{{ $data->status}}</td>
                                <td>
                                    <a href="" class="btn btn-primary"
                                        id='editTrans' 
                                        data-id="{{$data->id}}" data-toggle="modal"
                                        data-target="#editModal">
                                        Edit
                                    </a>
                                    <form id="delete-form-{{$data->id}}" + action="{{ route('transaction.destroy', $data->id)}}"
                                        method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger delete">Hapus</button>
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

<!-- Edit Modal Transaction -->
<div class="modal fade" id="editTrans" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="">
    <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Data Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="padding-bottom: 5px">
                    <form action="">
                        <input type="hidden" id="edit_id" />
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">No Kartu Keluarga</label>
                            <div class="col-sm-10">
                                <input id="edit_nomor" type="text" name="family_card_id" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Nominal</label>
                            <div class="col-sm-10">
                                <input id="edit_amount" type="text" name="jumlah" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Tahun</label>
                            <div class="col-sm-10">
                                <input id="edit_tahun" type="text" name="tahun" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Bulan</label>
                            <div class="col-sm-10">
                                <input id="edit_bulan" type="text" name="bulan" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="belum">Belum Membayar</option>
                                    <option value="tunggu">Menunggu Konfirmasi</option>
                                    <option value="lunas">Lunas</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">receipt</label>
                            <div class="col-sm-10">
                                <input type="text" id="edit_status" name="receipt" class="form-control">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnUpdateTrans">Update Data</button>
                </div>
            </div>
        </div>
    </form>
</div>
<!-- End Edit Modal Transaction -->



<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    $(document).on('click', '#editTrans', function (e) {
            e.preventDefault();
            const id = $(this).data('id');
            console.log(id);
            $('#editTrans').modal('show');
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
                url: "/dashboard/transaction/" + id + "/edit",
                type: "GET",
                success: function (response) {                
                    $('#edit_id').val(id);
                    $('#edit_nomor').val(response.family_card_id);
                    $('#edit_amount').val(response.email);
                    $('#edit_nik').val(response.jumlah);
                    $('#edit_tahun').val(response.tahun);
                    $('#edit_bulan').val(response.bulan);
                    $('#edit_status').val(response.status);
                    $('#edit_receipt').val(response.receipt);
                    
                    // if (response.status == 404) {
                    //     console.log(response);
                    //     // $('#success_message').addClass('alert alert-success');
                    //     // $('#success_message').text(response.message);
                    //     // $('#tarifModalUpdate').modal('hide');
                    // } else {
                    //     console.log("TIDAK MASUK");
                    //     // console.log(response.land.category_name);
                    //     // $('#id').val(id);
                    //     // $('#category_name').val(response.tarif.category_name);
                    //     // $('#amount').val(response.tarif.amount);
                    // }
                }
            });
            $('.close').find('input').val('');
        });

    $("#btnUpdateUser").click(() => {
        const id = $("#edit_id").val();
        const family_card_id = $("#edit_nomor").val();
        const jumlah = $("#edit_amount").val();  
        const tahun = $("#edit_tahun").val();  
        const bulan = $("#edit_bulan").val();  
        const status = $("#edit_status").val(); 
        const receipt = $("#edit_receipt").val(); 

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
            url: "/dashboard/transaction/" + id,
            type: "PUT",
            data: {
                family_card_id: family_card_id,
                jumlah: jumlah,
                tahun: tahun,
                bulan: bulan,
                status: status,
                receipt: receipt
            },
            success: function (response) {
                $('#editTrans').modal('hide');
                $('#success_message').addClass('alert alert-success');
                $('#success_message').text("Data Transaction Berhasil Di Update!");
                setTimeout(() => {
                    location.reload();
                }, 5000);
            }
        });
    });
</script>