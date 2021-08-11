@extends('adminlayout.layout')
@section('content')
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div style="margin-left: 10px;" class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-list"></i> Bot Setting List</h1>
        </div>
        <!-- DataTales Example -->
        <!-- Copy drisini -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Setting Bot Telegram</h6>
            </div>
            <div class="card-body">
            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-times"></i> 
                    {{ Session::get('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                @if (isset($errors) && $errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                    {{$error}}
                    @endforeach
                </div>
                @endif
                @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check"></i> {{Session::get('success')}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                @endif
                @if (!empty($success))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fa fa-check"></i> {{$success}}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
                @endif
            <div class="table-responsive">
            @if(count($botsetting)<3)
                <a class= "btn btn-success text-white" href="{{route('botsetting-create')}}"><i class="fas fa-plus"></i> Tambah Data Setting</a>
            @endif
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Jenis Bot</th>
                        <th>Setting Periode</th>
                        <th>Setting Waktu</th>
                        <th>Akses Petinggi</th>
                        <th>Akses Admin</th>
                        <th>Akses Koprodi</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($botsetting as $bs)
                    <tr>
                        <td align="center">
                            <a href="{{route('botsetting-show',$bs->id)}}" class="btn btn-info btn-sm"><i class="fas fa-pencil-alt"></i></a>
                        </td>
                        <td>
                            @if($bs->jenis_bot==1)
                                Bot Kepangkatan
                            @elseif($bs->jenis_bot==2)
                                Bot Pensiun
                            @elseif($bs->jenis_bot==3)
                                Bot Tugas Belajar
                            @endif
                        </td>
                        <td>
                            {{$bs->setting_periode}}x
                        </td>
                        <td>
                            @if($bs->setting_waktu==182)
                                6 Bulan
                            @elseif($bs->setting_waktu==365)
                                Setahun
                            @endif
                        </td>
                        <td>
                            @if($bs->akses_petinggi==1)
                                <span class="badge badge-pill badge-success">Hidup</span>
                            @else
                                <span class="badge badge-pill badge-danger">Mati</span>
                            @endif
                        </td>
                        <td>
                            @if($bs->akses_admin==1)
                                <span class="badge badge-pill badge-success">Hidup</span>
                            @else
                                <span class="badge badge-pill badge-danger">Mati</span>
                            @endif
                        </td>
                        <td>
                            @if($bs->akses_koprodi==1)
                                <span class="badge badge-pill badge-success">Hidup</span>
                            @else
                                <span class="badge badge-pill badge-danger">Mati</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
@endsection