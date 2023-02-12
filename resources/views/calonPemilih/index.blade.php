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
