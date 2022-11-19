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
    <div class="section-body">
        <div id="success_message"></div>
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="filer d-flex align-items-center">
                    <select class="form-control mr-2" name="filter_bulan">
                        <option {{($bulan == 'januari')?'selected':"";}} value="januari">Januari</option>
                        <option {{($bulan == 'februari')?'selected':"";}} value="Februari">Februari</option>
                        <option {{($bulan == 'maret')?'selected':"";}} value="maret">Maret</option>
                        <option {{($bulan == 'april')?'selected':"";}} value="april">April</option>
                        <option {{($bulan == 'mei')?'selected':"";}} value="mei">Mei</option>
                        <option {{($bulan == 'juni')?'selected':"";}} value="juni">Juni</option>
                        <option {{($bulan == 'juli')?'selected':"";}} value="juli">Juli</option>
                        <option {{($bulan == 'agustus')?'selected':"";}} value="agustus">Agustus</option>
                        <option {{($bulan == 'september')?'selected':"";}} value="september">September</option>
                        <option {{($bulan == 'oktober')?'selected':"";}} value="oktober">Oktober</option>
                        <option {{($bulan == 'november')?'selected':"";}} value="november">November</option>
                        <option {{($bulan == 'desember')?'selected':"";}} value="desember">Desember</option>
                    </select>
                    <input class="form-control mr-2" type="number" name="filter_tahun" value="{{$tahun}}" style="height: 100%;" placeholder="tahun">
                    <button id="search_filter" class="btn btn-info">Search</button>
                </div>
                <div id="export" class="export">
                    <?php $rt_rw = implode('-',explode('/',session()->get('user')->rt_rw));?>
                    <button class="btn btn-info" onclick="funcExport()">eksport</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor</th>
                            <th>Nama Kepala Keluarga</th>
                            <th>RT RW</th>
                            <th>Jumlah Pembayaran</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px!important" id="table_body">
                        @foreach ($detail_report as $key => $data)                            
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data->nomor }}</td>
                                <td>{{ $data->nama }}</td>
                                <td>{{ $data->rt_rw }}</td>
                                <td>{{ $data->jumlah }}</td>
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
    let bulan = '{{ $bulan }}';
    let tahun = '{{ $tahun }}';
    let rt_rw = '{{ $rt_rw }}';

    String.prototype.replaceAt = function(index, replacement) {
        return this.substring(0, index) + replacement + this.substring(index + replacement.length);
    }

    const funcExport = function(){
        window.location.href = `{{URL::to('/dashboard/report/print_DetailTunggakan/${rt_rw}/${tahun}/${bulan}')}}`;
    }

    $(document).ready(function(){
        const table_rekap = @json($detail_report);

        if(table_rekap.length != 0){
            let count = 0;
            console.log(table_rekap);
            $("#table_body").html('');
            table_rekap.forEach((data) => {
                count += 1;
                
                $("#table_body").append(`
                    <tr>
                        <td>${count}</td>
                        <td>${data.nomor}</td>
                        <td>${data.nama}</td>
                        <td>${data.rt_rw}</td>
                        <td>${data.jumlah}</td>
                    </tr>
                `);
            })
        }else{
            $("#table_body").html(`
                <tr>
                    <td colspan="5" class="text-center">Data Masih Kosong</td>
                </tr>
            `);
        }

        $('#search_filter').click(function(){
            bulan = $("select[name='filter_bulan']").find(':selected').val()
            tahun = $("input[name='filter_tahun']").val()
            
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "/dashboard/report/filterRt_tunggakan/" + tahun + "/" + bulan,
                type: "GET",
                success: function (response) {
                    if(response.length != 0){
                        let count = 0;
                        $("#table_body").html('');
                        response.forEach((data) => {
                            count += 1;
                            let rt_rw = data['rt_rw'].replaceAt(3, "-");
                            
                            $("#table_body").append(`
                                <tr>
                                    <td>${count}</td>
                                    <td>${data.nomor}</td>
                                    <td>${data.nama}</td>
                                    <td>${data.rt_rw}</td>
                                    <td>${data.jumlah}</td>
                                </tr>
                            `);
                        })
                    }else{
                        $("#table_body").html(`
                            <tr>
                                <td colspan="5" class="text-center">Data Masih Kosong</td>
                            </tr>
                        `);
                    }

                }
            });
        })
    })
</script>
@endpush