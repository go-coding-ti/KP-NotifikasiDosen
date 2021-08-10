@extends('adminlayout.layout')
@section('content')
<div class="container-fluid">
    @if (Session::has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-times"></i> 
        {{ Session::get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
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
    <div style="margin-left: 10px;" class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-list"></i> Tambah Data Setting Bot</h1>
    </div>
    <form method="POST" enctype="multipart/form-data" action="{{route('botsetting-update', $bs->id)}}">
    @csrf
    <div class="card shadow">
        <div class="form-group card-header shadow">
            <div class="row">
                <div class="col">
                    <h3 class="font-weight-bold text-primary"><i class="fas fa-university"></i> Data Setting Bot</h3>
                </div>
            </div>
        </div>
        <div class="form-group card-body">    
            <div class="row">
                <div class='col'>
                    <label for="jenis_bot" class="font-weight-bold text-dark">Jenis Bot</label>
                    <select class="form-control" id="jenis_bot" name="jenis_bot">
                        <option value="" {{ $bs->jenis_bot==NULL ? 'selected' : '' }}>Pilih Jenis Bot</option>
                        <option value="1" {{ $bs->jenis_bot==1 ? 'selected' : '' }}>Bot Kepangkatan</option>
                        <option value="2" {{ $bs->jenis_bot==2 ? 'selected' : '' }}>Bot Pensiun</option>
                        <option value="3" {{ $bs->jenis_bot==3 ? 'selected' : '' }}>Bot Tugas Belajar</option>
                    </select>
                    <small style="color: red">
                        @error('jenis_bot')
                            {{$message}}
                        @enderror
                    </small>
                </div>
                <div class='col'>
                    <label for="periode" class="font-weight-bold text-dark">Jenis Periode Pengiriman Notifikasi</label>
                    <select class="form-control" id="periode" name="periode">
                        <option value="" {{ $bs->setting_periode==NULL ? 'selected' : '' }}>Pilih Periode Pengiriman Notifikasi</option>
                        <option value="1" {{ $bs->setting_periode==1 ? 'selected' : '' }}>1x</option>
                        <option value="2" {{ $bs->setting_periode==2 ? 'selected' : '' }}>2x</option>
                    </select>
                    <small style="color: red">
                        @error('periode')
                            {{$message}}
                        @enderror
                    </small>
                </div>
                <div class='col'>
                    <label for="waktu" class="font-weight-bold text-dark">Waktu Pengiriman Notifikasi</label>
                    <select class="form-control" id="waktu" name="waktu">
                        <option value="" {{ $bs->setting_waktu==NULL ? 'selected' : '' }}>Pilih Waktu Pengiriman Notifikasi</option>
                        <option value="182" {{ $bs->setting_waktu==182 ? 'selected' : '' }}>6 Bulan</option>
                        <option value="365" {{ $bs->setting_waktu==365 ? 'selected' : '' }}>Setahun</option>
                    </select>
                    <small style="color: red">
                        @error('waktu')
                            {{$message}}
                        @enderror
                    </small>
                </div>
                </div>
                <div class="row">
                <div class='col'>
                    <label for="aksespetinggi" class="font-weight-bold text-dark">Notifikasi Petinggi</label>
                    <select class="form-control" id="aksespetinggi" name="aksespetinggi">
                        <option value="" {{ $bs->akses_petinggi==NULL ? 'selected' : '' }}>Pilih Opsi Notifikasi Petinggi</option>
                        <option value="1" {{ $bs->akses_petinggi==1 ? 'selected' : '' }}>Hidup</option>
                        <option value="0" {{ $bs->akses_petinggi==0 ? 'selected' : '' }}>Mati</option>
                    </select>
                    <small style="color: red">
                        @error('aksespetinggi')
                            {{$message}}
                        @enderror
                    </small>
                </div>
                
                <div class='col'>
                    <label for="aksesadmin" class="font-weight-bold text-dark">Notifikasi Admin</label>
                    <select class="form-control" id="aksesadmin" name="aksesadmin">
                        <option value="" {{ $bs->akses_admin==null ? 'selected' : '' }}>Pilih Opsi Notifikasi Admin</option>
                        <option value="1" {{ $bs->akses_admin==1 ? 'selected' : '' }}>Hidup</option>
                        <option value="0" {{ $bs->akses_admin==0 ? 'selected' : '' }}>Mati</option>
                    </select>
                    <small style="color: red">
                        @error('aksesadmin')
                            {{$message}}
                        @enderror
                    </small>
                </div>
                <div class='col'>
                    <label for="akseskoprodi" class="font-weight-bold text-dark">Notifikasi Koprodi</label>
                    <select class="form-control" id="akseskoprodi" name="akseskoprodi">
                        <option value="" {{ $bs->akses_koprodi==null ? 'selected' : '' }}>Pilih Opsi Notifikasi Koprodi</option>
                        <option value="1" {{ $bs->akses_koprodi==1 ? 'selected' : '' }}>Hidup</option>
                        <option value="0" {{ $bs->akses_koprodi==0 ? 'selected' : '' }}>Mati</option>
                    </select>
                    <small style="color: red">
                        @error('akseskoprodi')
                            {{$message}}
                        @enderror
                    </small>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Menambah Data?')"><i class="fas fa-save"></i> Simpan</button>
                    <a href="{{route('botsetting-index')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection