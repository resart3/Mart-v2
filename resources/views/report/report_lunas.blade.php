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
                <div class="filer">
                    {{$bulan}}
                    <select name="filter_bulan">
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
                    <input type="number" name="filter_tahun" value="{{$tahun}}">
                    <button id="search_filter" class="btn btn-info">Search</button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable" class="table-bordered table-md table">
                        <thead>
                        <tr>
                            <th style="width: 5%">No</th>
                            <th>RT RW</th>
                            <th>Jumlah</th>
                            <th style="width: 15%">Action</th>
                        </tr>
                        </thead>
                        <tbody style="font-size: 14px!important">
                        @foreach ($table_rekap as $key => $data)                            
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $data['rt_rw'] }}</td>
                                <td>{{ $data['jumlah'] }}</td>
                                <?php $rt_rw =implode('-',explode('/',$data['rt_rw']));?>
                                <td>
                                    <a href="{{ route('detail_jumlah', ['rt_rw' => $rt_rw, 'bulan' => $bulan, 'tahun' => $tahun])}}" class="btn btn-outline-primary">Detail</a>
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

@push('scripts')

<script>
    $(document).ready(function(){
        $('#search_filter').click(function(){
            let bulan = $("select[name='filter_bulan']").find(':selected').val()
            let tahun = $("input[name='filter_tahun']").val()
            
            $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},            
            url: "/dashboard/report/filer_jumlah/" + tahun + "/" + bulan,
            type: "GET",
            success: function (response) {
                console.log(response)
            }
        });
        })
    })
</script>

@endpush