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
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                        <tr class="text-center">
                            <th>No</th>
                            <th>Nomor KK</th>
                            <th>Nama Kepala Keluarga</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                        @foreach ($familyCard as $key => $data)
                            <tr class="text-center">
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nomor }}</td>
                                <td>
                                    @foreach ($data->with_family_head as $head )
                                        {{ $head->nama }}
                                    @endforeach
                                </td>
                                <td>
                                    <a href="{{ route('detail_transaction', ['nomor' => $data->nomor, 'tahun' => date('Y')]) }}" class="btn btn-primary"> Detail </a>
                                </td>
                                {{-- <td class="d-flex justify-content-center align-items-center">
                                    <a href="" class="btn btn-primary"
                                        id='editTrans' data-id="{{$data['id']}}" data-toggle="modal"
                                        data-target="#editTransModal"> Edit
                                    </a>
                                    <form id="delete-form-{{$data['id']}}" + action="{{ route('transaction.destroy', $data['id'])}}"
                                        method="POST">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-danger delete" onclick="return confirm('Apakah anda yakin ingin hapus?');">Hapus</button>
                                    </form>
                                </td> --}}
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
<div class="modal fade" id="editTransModal" tabindex="-1" role="dialog" aria-hidden="true">
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
                                <input id="edit_no_kk" type="text" name="family_card_id" 
                                    class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Nominal</label>
                            <div class="col-sm-10">
                                <input id="edit_amount" type="text" name="jumlah" class="form-control" 
                                    readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Tahun</label>
                            <div class="col-sm-10">
                                <input id="edit_tahun" type="text" name="tahun" class="form-control" 
                                    readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Bulan</label>
                            <div class="col-sm-10">
                                <input id="edit_bulan" type="text" name="bulan" class="form-control" 
                                    readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Status</label>
                            <div class="col-sm-10">
                                <select class="form-control" id="edit_status" name="status" required>
                                    <option value="Belum Membayar">Belum Membayar</option>
                                    <option value="Menunggu Konfirmasi">Menunggu Konfirmasi</option>
                                    <option value="Lunas">Lunas</option>
                                    <option value="Tidak Valid">Tidak Valid</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">receipt</label>
                            <div class="col-sm-10" id="receipt">
                                <img src="" class="h-100 w-50" alt="receipt_image" id="edit_receipt" name="receipt">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnUpdateTrans">Update Data</button>
                </div>
            </div>
        </div>
</div>
<!-- End Edit Modal Transaction -->



<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
{{-- <script>
    $(document).on('click', '#editTrans', function (e) {
        const id = $(this).data('id');
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
            url: "/dashboard/transaction/" + id + "/edit",
            type: "GET",
            success: function (response) {
                $('#edit_id').val(id);
                $('#edit_no_kk').val(response.family_card_id);

                const rupiah = (number)=>{
                    return new Intl.NumberFormat("id-ID", {
                        style: "currency",
                        currency: "IDR"
                    }).format(number);
                }
                $('#edit_amount').val(rupiah(response.jumlah));
                $('#edit_tahun').val(response.tahun);
                $('#edit_bulan').val(response.bulan);
                $('#edit_status').val(response.status);
                $("#edit_receipt").attr("src", `{{ URL::to('/') }}/assets/images/transaction/${response.family_card_id}/${response.receipt}`);
            }
        });
        $('.close').find('input').val('');
    });

    $("#btnUpdateTrans").click(() => {
        const id = $("#edit_id").val();
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
            url: "/dashboard/transaction/" + id,
            type: "PUT",
            data: {
                status: $("#edit_status").val(),
            },
            success: function (response) {
                $('#editTransModal').modal('hide');
                window.scrollTo(0, 0);
                $('#success_message').addClass('alert alert-success');
                $('#success_message').text("Data Transaction Berhasil Di Update!");
                setTimeout(() => {
                    location.reload();
                }, 1000);
            }
        });
    });
</script> --}}