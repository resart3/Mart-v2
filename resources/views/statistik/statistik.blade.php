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
                <div class="aligns-items-center d-inline-block text-center">
                    <h2>Table Data</h2>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="dataTable_calon" class="table-bordered table-md table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Deskripsi</th>
                            <th>Ket</th>
                        </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Jumlah Kepala Rumah Tangga</td>
                                <td>{{$data['krt']->jumlah}}</td>
                                <td>KRT</td>
                            </tr>
                            <tr>
                                <td>Jumlah Warga Laki Laki</td>
                                <td>{{$data['male']->jumlah}}</td>
                                <td>Jiwa</td>
                            </tr>
                            <tr>
                                <td>Jumlah Warga Perempuan</td>
                                <td>{{$data['female']->jumlah}}</td>
                                <td>Jiwa</td>
                            </tr>
                            <tr>
                                <td>Jumlah Warga Lansia(>60 tahun)</td>
                                <td>{{$data['lansia']->jumlah}}</td>
                                <td>Jiwa</td>
                            </tr>
                            <tr>
                                <td>Jumlah Warga Wanita Usia Subur(12-45 tahun)</td>
                                <td>{{$data['wanitaSubur']->jumlah}}</td>
                                <td>Jiwa</td>
                            </tr>
                            <tr>
                                <td>Jumlah Warga Usia 12-18 tahun</td>
                                <td>{{$data['usia12_18']->jumlah}}</td>
                                <td>Jiwa</td>
                            </tr>
                            <tr>
                                <td>Jumlah Warga Usia 6-12 tahun</td>
                                <td>{{$data['usia6_12']->jumlah}}</td>
                                <td>Jiwa</td>
                            </tr>
                            <tr>
                                <td>Jumlah Warga Usia 0-6 tahun</td>
                                <td>{{$data['usia0_6']->jumlah}}</td>
                                <td>Jiwa</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
         <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="aligns-items-center d-inline-block text-center">
                    <h2>Table Data</h2>
                </div>
            </div>
            <div class="card-body">
                <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script>
    var xValues = ["Kepala Rumah Tangga", "Warga Laki Laki", "Warga Perempuan", 
    "Warga Lansia(>60 tahun)", "Warga Wanita Usia Subur(12-45 tahun)","Warga Usia 12-18 tahun",
    "Warga Usia 6-12 tahun","Warga Usia 0-6 tahun"];
    var yValues = [55, 49, 44, 24, 15, 50, 50, 50];
    var barColors = ["rgba(0,0,255,1.0)",
  "rgba(0,0,255,0.9)",
  "rgba(0,0,255,0.8)",
  "rgba(0,0,255,0.7)",
  "rgba(0,0,255,0.6)",
  "rgba(0,0,255,0.5)",
  "rgba(0,0,255,0.4)",
  "rgba(0,0,255,0.3)",
];

    new Chart("myChart", {
    type: "bar",
    data: {
        labels: xValues,
        datasets: [{
        backgroundColor: barColors,
        data: yValues
        }]
    },
    options: {
        legend: {display: false},
        title: {
        display: true,
        text: "World Wine Production 2018"
        }
    }
    });
</script>
@endpush
