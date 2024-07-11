@extends('layout.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Normalisasi dan Hasil</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Normalisasi dan Hasil</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <!-- /.card-header -->
                    <div class="card-body p-2">
                        @if($Nilaibobots)
                        <div class="row p-2 align-items-center">
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <a href="{{ route('admin.hitungSAW') }}" class="btn btn-dark btn-block">Mulai
                                    Perhitungan</a>
                            </div>
                            <div class="col-md-4"></div>
                        </div>

                        @if (session('ranked_data_saw'))
                            <h3>Nilai Asli</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        @foreach (array_keys(session('ranked_data_saw')[0]->nilai_asli) as $kriteria)
                                            @if (!in_array($kriteria, ['id', 'name', 'created_at', 'updated_at']))
                                                <th>{{ $kriteria }}</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (session('ranked_data_saw') as $item)
                                        <tr>
                                            <td>{{ $item->nama }}</td>
                                            @foreach ($item->nilai_asli as $kriteria => $nilai)
                                                @if (!in_array($kriteria, ['id', 'name', 'created_at', 'updated_at']))
                                                    <td>{{ $nilai }}</td>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>Min/Max</th>
                                        @foreach (session('ranked_data_saw')[0]->minmax['max'] as $kriteria => $nilai)
                                            <th>
                                                Max: {{ $nilai }}<br>
                                                @if ($tipekriteria[$kriteria] === 'Benefit')
                                                    @if (isset(session('ranked_data_saw')[0]->minmax['min'][$kriteria]))
                                                        Min:
                                                        {{ session('ranked_data_saw')[0]->minmax['min'][$kriteria] }}
                                                    @endif
                                                @elseif ($tipekriteria[$kriteria] === 'Cost')
                                                    @if (isset(session('ranked_data_saw')[0]->minmax['max'][$kriteria]))
                                                        Max:
                                                        {{ session('ranked_data_saw')[0]->minmax['max'][$kriteria] }}
                                                    @endif
                                                @endif
                                            </th>
                                        @endforeach
                                    </tr>
                                </tfoot>
                            </table>

                            <h3>Normalisasi</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        @foreach (array_keys(session('ranked_data_saw')[0]->normalisasi) as $kriteria)
                                            <th>{{ $kriteria }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (session('ranked_data_saw') as $item)
                                        <tr>
                                            <td>{{ $item->nama }}</td>
                                            @foreach ($item->normalisasi as $kriteria => $nilai)
                                                <td>{{ number_format($nilai, 4) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <h3>Preferensi</h3>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Nama</th>
                                        <th>Nilai Preferensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (session('ranked_data_saw') as $item)
                                        <tr>
                                            <td>{{ $item->rank_saw }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ number_format($item->nilai_preferensi_saw, 4) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="alert alert-warning" role="alert">
                                Belum ada data yang dihitung. Silakan klik "Mulai Perhitungan" untuk menghitung.
                            </div>
                        @endif
                        @else
                        <div class="alert alert-warning" role="alert">
                            <h5>Anda Belum memasukkan Bobot</h5>
                        </div>
                        @endif
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </section>
        <!-- /.content -->
    </div>
@endsection
