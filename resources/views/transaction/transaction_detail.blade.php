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
                <div class="d-flex justify-content-start align-items-center w-50">
                    <span class="mr-2">Input Tahun</span>
                    <input class="form-control form-control-sm border border-secondary rounded-0 w-25 mr-2" type="number" 
                        min="1900" max="2099" id="search_tahun"/>
                    <button class="btn btn-outline-danger" type="submit" onclick="input_tahun({{ $nomor }})">Search</button>
                </div>
                <div class="table-responsive">
                    <table id="transactionTable" class="table-bordered table-md table">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Jumlah</th>
                                <th>Tahun</th>
                                <th>Bulan</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                            @foreach ($arrDataTransaksiSorted as $key => $data)
                                <tr class="text-center">
                                    <td>{{ $key + 1 }}</td>
                                    <td>Rp {{ number_format($data["jumlah"],0,",",".") }}</td>
                                    <td>{{ $data["tahun"] }}</td>
                                    <td>{{ $data["bulan"] }}</td>
                                    <td>{{ $data["status"] }}</td>
                                    <td>
                                        <div class="mr-1">
                                            @if ($data['status'] == 'Belum Membayar')
                                                <a href="#" class="btn btn-primary"
                                                    onclick="get_input_data({{ $nomor }}, {{$data['jumlah']}}, 
                                                    {{$data['tahun']}}, '{{$data['bulan']}}')"
                                                    data-toggle="modal" data-target="#inputTransactionModal">
                                                    Input Transaksi
                                                </a>
                                            @elseif ($data['status'] == 'Lunas')
                                                <a href="#" class="btn btn-primary"
                                                    onclick="get_update_data({{ $nomor }}, {{$data['jumlah']}}, 
                                                    {{$data['tahun']}}, '{{$data['bulan']}}')"
                                                    data-toggle="modal" data-target="#updateTransactionModal">
                                                    Update
                                                </a>
                                                <a href="" class="btn btn-outline-primary" 
                                                    onclick="get_receipt_image({{ $nomor }}, {{$data['tahun']}}, 
                                                    '{{$data['bulan']}}')" data-toggle="modal" 
                                                    data-target="#receiptModal">
                                                    Bukti Bayar
                                                </a>
                                                <form action="{{ route('delete_transaction', ['nomor' => $nomor, 'tahun' => $data['tahun'], 'bulan' => $data['bulan']])}}" 
                                                    method="POST" class="mt-1">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-danger delete" 
                                                        onclick="return confirm('Apakah anda yakin hapus?');">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @elseif ($data['status'] == 'Tidak Tersedia')
                                                -
                                            @endif
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

{{-- Modal Input Transaksi --}}
<div class="modal fade" id="inputTransactionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="{{ route('transaction.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Input Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="padding-bottom: 5px">
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Nomor KK</label>
                            <div class="col-sm-10">
                                <input id="nomor_kk" type="text" name="nomor_kk" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Jumlah</label>
                            <div class="col-sm-10">
                                <input id="amount" type="text" name="amount" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Tahun</label>
                            <div class="col-sm-10">
                                <input id="tahun" type="text" name="tahun" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Bulan</label>
                            <div class="col-sm-10">
                                <input id="bulan" type="text" name="bulan" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Bukti Pembayaran</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control-file" id="receipt" name="receipt" required>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Input</button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Modal Update Transaksi --}}
<div class="modal fade" id="updateTransactionModal" tabindex="-1" role="dialog" aria-hidden="true">
    <form action="{{ route('transaction.update', $nomor) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Transaksi</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" style="padding-bottom: 5px">
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Nomor KK</label>
                            <div class="col-sm-10">
                                <input id="update_nomor_kk" type="text" name="nomor_kk" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Jumlah</label>
                            <div class="col-sm-10">
                                <input id="update_amount" type="text" name="amount" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Tahun</label>
                            <div class="col-sm-10">
                                <input id="update_tahun" type="text" name="tahun" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Bulan</label>
                            <div class="col-sm-10">
                                <input id="update_bulan" type="text" name="bulan" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group row mb-4">
                            <label class="col-sm-2 col-form-label">Bukti Pembayaran</label>
                            <div class="col-sm-10">
                                <input type="file" class="form-control-file" id="receipt" name="receipt">
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>

{{-- Modal View Bukti Pembayaran --}}
<div class="modal fade bd-example-modal-lg" id="receiptModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Bukti Pembayaran</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body" style="padding-bottom: 5px">
                <div class="w-100">
                    <img src="" alt="Receipt Image" id="receipt_image" style="max-width: 100%;">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script>
    const input_tahun = (nomor) => {
        const tahun = $("#search_tahun").val();
        window.location.href = `{{URL::to('/dashboard/transaction/${nomor}/${tahun}')}}`;
    }

    const get_input_data = (nomor, jumlah, tahun, bulan) => {
        $("#nomor_kk").val(nomor);
        $("#amount").val(jumlah);
        $("#tahun").val(tahun);
        $("#bulan").val(bulan);
    }

    const get_update_data = (nomor, jumlah, tahun, bulan, status) => {
        $("#update_nomor_kk").val(nomor);
        $("#update_amount").val(jumlah);
        $("#update_tahun").val(tahun);
        $("#update_bulan").val(bulan);
    }

    const get_receipt_image = (nomor, tahun, bulan) => {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
            url: "/dashboard/transaction/" + nomor + "/" + tahun + "/" +bulan,
            type: "GET",
            success: function (response) {
                $("#receipt_image").attr("src", `{{ URL::to('/') }}/assets/images/transaction/${nomor}/${response}`);
            }
        });
    }

    $(document).ready(function () {
        $('#transactionTable').DataTable({
            paging: false,
            info: false,
        });
    });
</script>
