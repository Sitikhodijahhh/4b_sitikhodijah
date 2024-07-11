@extends('layout.main')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Bobot kepentingan</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Bobot kepentingan</li>
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
                    <div class="col-12">
                        <button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#modal-tambah">Tambah Data</button>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Responsive Hover Table</h3>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama</th>
                                            @foreach($bobot as $a)
                                            <th>{{ $a }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $d->user->name }}</td>
                                                @foreach($bobot as $a)
                                                <td>{{ $d->$a }}</td>
                                                @endforeach
                                                <td>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-edit{{ $d->id }}"><i class="fas fa-pen"></i> Edit</button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modal-hapus{{ $d->id }}"><i class="fas fa-trash-alt"></i> Hapus</button>
                                                </td>
                                            </tr>
                                            <!-- Modal Edit -->
                                            <div class="modal fade" id="modal-edit{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Edit Data Kriteria</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="{{ route('admin.Bobot.update', $d->id) }}" method="POST">
                                                                @csrf
                                                                @method('PUT')
                                                                <div class="form-group">
                                                                    <label for="user_id">Nama</label>
                                                                    <select class="form-control" name="user_id">
                                                                        @foreach($user as $users)
                                                                            <option value="{{ $users->id }}" {{ $d->user_id == $users->id ? 'selected' : '' }}>
                                                                                {{ $users->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                                @foreach($bobot as $a)
                                                                    <div class="form-group">
                                                                        <label for="{{ $a }}">{{ ucfirst($a) }}</label>
                                                                        <input type="number" class="form-control" name="{{ $a }}" value="{{ $d->$a }}" min="0" max="100" step="0.01">
                                                                    </div>
                                                                @endforeach
                                                                <div class="modal-footer justify-content-between">
                                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Modal Hapus -->
                                            <div class="modal fade" id="modal-hapus{{ $d->id }}">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h4 class="modal-title">Konfirmasi Hapus Data</h4>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah kamu yakin ingin menghapus data kriteria <b>{{ $d->name }}</b>?</p>
                                                        </div>
                                                        <div class="modal-footer justify-content-between">
                                                            <form action="{{ route('admin.Bobot.delete', $d->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Ya, Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                    </div>
                </div>
                <!-- Modal Tambah -->
                <div class="modal fade" id="modal-tambah">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Tambah Data Alternatif</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('admin.Bobot.store') }}" method="POST">
                                    @csrf
                                    <div class="form-group">
                                        <label for="user_id">Nama</label>
                                        <select class="form-control" name="user_id">
                                            @foreach($user as $users)
                                                <option value="{{ $users->id }}">{{ $users->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @foreach($bobot as $a)
                                        <div class="form-group">
                                            <label for="{{ $a }}">{{ ucfirst($a) }}</label>
                                            <input type="number" class="form-control" name="{{ $a }}">
                                        </div>
                                    @endforeach
                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

                <!-- /.modal -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
