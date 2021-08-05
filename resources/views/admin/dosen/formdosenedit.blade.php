@extends('adminlayout.layout')
@section('content')
@section('add_js')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
  function readURL(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    
    reader.onload = function(e) {
      $('#propic').attr('src', e.target.result);
    }
    
    reader.readAsDataURL(input.files[0]); // convert to base64 string
  }
}

$("#profile_image").change(function() {
  readURL(this);
});
</script>
<script>
    $('.file-upload').file_upload();
</script>
@endsection
@section('add_css')

@endsection
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
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-list"></i> Detail Data Dosen</h1>
    </div>
    <form method="POST" enctype="multipart/form-data" action="/admin/datauser/dosen/updatedetaildosen/{{$dosen->nip}}">
    @csrf
    <div class="card shadow">
            <div class="form-group card-header shadow">
                <div class="row">
                    <div class="col">
                        <h3 class="font-weight-bold text-primary"><i class="fas fa-user"></i> Data Dosen</h3>
                    </div>
                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-success" onclick="return confirm('Apakah Anda Yakin Ingin Mengupdate Data?')"><i class="fas fa-save"></i> Update</button>
                        <a href="{{route('admin-home')}}" class="btn btn-danger"><i class="fas fa-times"></i> Cancel</a>
                    </div>
                </div>
                </div>
            </div>
            <div class="form-group card-body">
                <div class="row align-items-center">
                    <div class="col col-3 ">
                        <div style="margin-top: -40px" class="row">
                            <div align="center" class='col'>
                                @if(isset($dosen->foto))
                                    <img src="{{asset('img/'.$dosen->foto)}}" class="mb-3" style="border:solid #000 3px;height:200px;width:150px;" id="propic">
                                @else
                                    <img src="{{asset('img/user.jpg')}}" class="mb-3" style="border:solid #000 3px;height:200px;width:150px;" id="propic">
                                @endif
                            </div>
                        </div>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="profile_image" name="profile_image" onchange="this.nextElementSibling.innerText = this.files[0].name">
                            <label for="profile_image" class="custom-file-label">.jpg/.png</label>
                            @if (is_null($dosen->foto))
                                <small style="color: red">
                                    *Belum Upload Foto Profil
                                </small>
                            @endif
                            <small style="color: red">
                                @error('profile_image')
                                    {{$message}}
                                @enderror
                            </small>
                        </div>
                    </div>

                    <div class="col col-sm-1"></div>

                    <div  class="col col-4">
                        <div class="row">
                            <div class='col'>
                                <label for="JenisSerdos" class="font-weight-bold text-dark">Jenis Serdos</label>
                                <select class="form-control" id="JenisSerdos" name="jenisserdos">
                                    @if(count($dosen->masteridpendidik)>0)
                                        <option value="" {{ $dosen->masteridpendidik[0]->jenis_id==NULL ? 'selected' : '' }}>Pilih Jenis Serdos</option>
                                        <option value="NIDN" {{ $dosen->masteridpendidik[0]->jenis_id=="NIDN" ? 'selected' : '' }}>NIDN</option>
                                        <option value="NIDK" {{ $dosen->masteridpendidik[0]->jenis_id=="NIDK" ? 'selected' : '' }}>NIDK</option>
                                        <option value="NUP" {{ $dosen->masteridpendidik[0]->jenis_id=="NUP" ? 'selected' : '' }}>NUP</option>
                                    @else
                                        <option value="" selected>Pilih Jenis Serdos</option>
                                        <option value="NIDN" {{old('jenisserdos')=="NIDN" ? 'selected' : ''}}>NIDN</option>
                                        <option value="NIDK" {{old('jenisserdos')=="NIDK" ? 'selected' : ''}}>NIDK</option>
                                        <option value="NUP" {{old('jenisserdos')=="NUP" ? 'selected' : ''}}>NUP</option>
                                    @endif
                                </select>
                                <small style="color: red">
                                    @error('jenisserdos')
                                        {{$message}}
                                    @enderror
                                </small>
                            </div>
                            <div class='col'>
                                <label for="nidn" class="font-weight-bold text-dark">NIDN/NIDK/NUP</label>
                                @if(count($dosen->masteridpendidik)>0)
                                    <input type="text" class="form-control" id="nidn" name="nidn" placeholder="Masukan NIDN/NIDK/NUP" value="{{$errors->any() ? old('nidn') : $dosen->masteridpendidik[0]->nidn_nidk_nup}}">
                                @else
                                    <input type="text" class="form-control" id="nidn" name="nidn" placeholder="Masukan NIDN/NIDK/NUP" value="{{$errors->any() ? old('nidn') : ''}}">
                                @endif
                                <small style="color: red">
                                    @error('nidn')
                                        {{$message}}
                                    @enderror
                                </small>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col'>
                                <label for="InputEmail" class="font-weight-bold text-dark">Email Aktif</label>
                                <input type="email" class="form-control" id="InputEmail" name="email" placeholder="Masukan Email" value="{{$errors->any() ? old('email') : $dosen->email_aktif}}">
                                <small style="color: red">
                                    @error('email')
                                        {{$message}}
                                    @enderror
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="col col-4">
                        <div class="row">
                            <div class='col'>
                        <label for="nip" class="font-weight-bold text-dark">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukan NIP" value="{{$errors->any() ? old('nip') : $dosen->nip}}">
                        <small style="color: red">
                            @error('nip')
                                {{$message}}
                            @enderror
                        </small>
                            </div>
                        </div>
                        <div class="row">
                            <div class='col'>
                                <label for="InputTelpRumah" class="font-weight-bold text-dark">Telp Rumah</label>
                                <input type="text" class="form-control" id="InputTelpRumahRumah" name="telprumah" placeholder="No. Telp Rumah" value="{{$errors->any() ? old('telprumah') : $dosen->telp_rumah}}">
                                <small style="color: red">
                                    @error('telprumah')
                                        {{$message}}
                                    @enderror
                                </small>
                            </div>
                            <div class='col'>
                                <label for="InputNoHp" class="font-weight-bold text-dark">No HP</label>
                                <input type="text" class="form-control" id="InputNoHp" name="nohp" placeholder="No. HP" value="{{$errors->any() ? old('nohp') :$dosen->no_hp}}">
                                <small style="color: red">
                                    @error('nohp')
                                        {{$message}}
                                    @enderror
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label for="gelardepan" class="font-weight-bold text-dark">Gelar Depan</label>
                        <input type="text" class="form-control" id="gelardepan" name="gelardepan" placeholder="Masukan Gelar Depan" value="{{$errors->any() ? old('gelardepan') : $dosen->gelar_depan}}">
                        <small>
                            Contoh jika gelar depan lebih dari satu (Prof. Dr.)
                        </small>
                        <small style="color: red">
                            @error('gelardepan')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="InputName" class="font-weight-bold text-dark">Nama Lengkap</label>
                        <input type="text" class="form-control" id="InputName" name="nama" placeholder="Masukan Nama Lengkap" value="{{$errors->any() ? old('nama') : $dosen->nama}}">
                        <small style="color: red">
                            @error('nama')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="gelarbelakang" class="font-weight-bold text-dark">Gelar Belakang</label>
                        <input type="text" class="form-control" id="gelarbelakang" name="gelarbelakang" placeholder="Masukan Gelar Belakang" value="{{$errors->any() ? old('gelarbelakang') : $dosen->gelar_belakang}}">
                        <small>
                            Contoh jika gelar belakang lebih dari satu (S.Kom., M.T)
                        </small>
                        <small style="color: red">
                            @error('gelarbelakang')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="InputTempatLahir" class="font-weight-bold text-dark">Tempat Lahir</label>
                        <input type="text" class="form-control" id="InputTempatLahir" name="tempatlahir" placeholder="Masukan Tempat Lahir" value="{{$errors->any() ? old('tempatlahir') : $dosen->tempat_lahir}}">
                        <small style="color: red">
                            @error('tempatlahir')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="InputTanggalLahir" class="font-weight-bold text-dark">Tanggal Lahir</label>
                        <input type="date" class="form-control" id="InputTanggalLahir" name="tanggallahir" value="{{$errors->any() ? old('tanggallahir') : $dosen->tanggal_lahir}}">
                        <small style="color: red">
                            @error('tanggallahir')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="JenisKelamin" class="font-weight-bold text-dark">Jenis Kelamin</label>
                        <select class="form-control" id="JenisKelamin" name="jeniskelamin">
                            <option value="" {{ $dosen->jenis_kelamin==NULL ? 'selected' : '' }}>Pilih JK</option>
                            @if($dosen->jenis_kelamin!=NULL)
                                <option value="Pria" {{ $dosen->jenis_kelamin=="Pria" ? 'selected' : '' }}>Pria</option>
                                <option value="Wanita" {{ $dosen->jenis_kelamin=="wanita" ? 'selected' : '' }}>Wanita</option>
                            @else
                                <option value="Pria" {{old('jeniskelamin')=="Pria" ? 'selected' : ''}}>Pria</option>
                                <option value="Wanita" {{old('jeniskelamin')=="Wanita" ? 'selected' : ''}}>Wanita</option>
                            @endif
                        </select>
                        <small style="color: red">
                            @error('jeniskelamin')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="TahunAjaran" class="font-weight-bold text-dark">Tahun Ajaran</label>
                        <select class="form-control" name="tahunAjaran" id="tahunAjaran">
                            @if(count($dosen->tahunajaran)>0)
                                <option value="" {{$dosen->tahunajaran[0]->tahun_ajaran==NULL ? 'selected' : ''}}>Pilih Tahun Ajaran</option>
                                @foreach ($tahun as $ta)
                                    <option value="{{$ta->id}}" {{$dosen->tahunajaran[0]->tahun_ajaran==$ta->id ? 'selected' : ''}}>{{$ta->semester}} - {{$ta->tahun_ajaran}}</option>
                                @endforeach
                            @else
                                <option value="" selected>Pilih Tahun Ajaran</option>
                                @foreach ($tahun as $ta)
                                    <option value="{{$ta->id}}" {{old('tahunAjaran')==$ta->id ? 'selected' : ''}}>{{$ta->semester}} - {{$ta->tahun_ajaran}}</option>
                                @endforeach
                            @endif
                        </select>
                        <small style="color: red">
                            @error('tahunAjaran')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="StatusDosen" class="font-weight-bold text-dark">Status Dosen</label>
                        <select class="form-control" name="statusdosen" id="StatusDosen">
                            @if(count($dosen->tmtstatus)>0)
                                <option value="" {{ $dosen->tmtstatus[0]->id_status_dosen==NULL ? 'selected' : '' }}>Pilih Status</option>
                                @foreach ($statusDosen as $status)
                                    <option value="{{$status->id_status_dosen}}" {{ $dosen->tmtstatus[0]->id_status_dosen==$status->id_status_dosen ? 'selected' : '' }}>{{$status->status_dosen}}</option>
                                @endforeach
                            @else
                                <option value="" selected>Pilih Status</option>
                                @foreach ($statusDosen as $status)
                                    <option value="{{$status->id_status_dosen}}" {{old('statusdosen')==$status->id_status_dosen ? 'selected' : ''}}>{{$status->status_dosen}}</option>
                                @endforeach
                            @endif
                        </select>
                        <small style="color: red">
                            @error('statusdosen')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="tmtStatusDosen" class="font-weight-bold text-dark">TMT Status Dosen</label>
                        @if(count($dosen->tmtstatus)>0)
                            <input type="date" class="form-control" id="tmtStatusDosen" name="tmtStatusDosen" value="{{$errors->any() ? old('tmtStatusDosen') : $dosen->tmtstatus[0]->tmt_status_dosen}}">
                        @else
                            <input type="date" class="form-control" id="tmtStatusDosen" name="tmtStatusDosen" value="{{$errors->any() ? old('tmtStatusDosen') : ''}}">
                        @endif
                        
                        <small style="color: red">
                            @error('tmtStatusDosen')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="StatusKepegawaian" class="font-weight-bold text-dark">Status Kepegawaian</label>
                        <select class="form-control" name="statusKepegawaian" id="StatusKepegawaian">
                            @if(count($dosen->tmtkepegawaian)>0)
                                <option value="" {{ $dosen->tmtkepegawaian[0]->id_status_kepegawaian==NULL ? 'selected' : '' }}>Pilih Status</option>
                                @foreach ($statusKepegawaian as $status)
                                    <option value="{{$status->id_status_kepegawaian}}" {{ $dosen->tmtkepegawaian[0]->id_status_kepegawaian==$status->id_status_kepegawaian ? 'selected' : '' }}>{{$status->status_kepegawaian}}</option>
                                @endforeach
                            @else
                                <option value="" selected>Pilih Status</option>
                                @foreach ($statusKepegawaian as $status)
                                    <option value="{{$status->id_status_kepegawaian}}" {{old('statusKepegawaian')==$status->id_status_kepegawaian ? 'selected' : ''}}>{{$status->status_kepegawaian}}</option>
                                @endforeach
                            @endif
                        </select>
                        <small style="color: red">
                            @error('statusKepegawaian')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="tmtStatusKepegawaian" class="font-weight-bold text-dark">TMT Status Kepegawaian</label>
                        @if(count($dosen->tmtkepegawaian)>0)
                            <input type="date" class="form-control" id="tmtStatusKepegawaian" name="tmtStatusKepegawaian" value="{{$errors->any() ? old('tmtStatusKepegawaian') : $dosen->tmtstatus[0]->tmt_status_dosen}}">
                        @else
                            <input type="date" class="form-control" id="tmtStatusKepegawaian" name="tmtStatusKepegawaian" value="{{$errors->any() ? old('tmtStatusKepegawaian') : ''}}">
                        @endif
                        <small style="color: red">
                            @error('tmtStatusKepegawaian')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="InputAlamatDomisili" class="font-weight-bold text-dark">Alamat Domisili (Alamat Tinggal Sekarang)</label>
                        <input type="text" class="form-control" id="InputAlamatDomisili" name="alamatdomisili"  placeholder="Masukan Alamat Domisili" value="{{ $errors->any() ? old('alamatdomisili') : $dosen->alamat_domisili}}">
                        <small style="color: red">
                            @error('alamatdomisili')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="InputAlamatRumah" class="font-weight-bold text-dark">Alamat Rumah (Sesuai KK/KTP)</label>
                        <input type="text" class="form-control" id="InputAlamatRumah" name="alamatrumah" placeholder="Masukan Alamat Rumah" value="{{ $errors->any() ? old('alamatrumah') : $dosen->alamat_rumah}}">
                        <small style="color: red">
                            @error('alamatrumah')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
            </div>
    </div>
    <br>
    <div class="card shadow">
            <div class="form-group card-header shadow">
                <div class="row">
                    <div class="col col-md-12">
                        <h3 class="font-weight-bold text-primary"><i class="fas fa-user-graduate"></i> Data Pendidikan Terakhir</h3>
                    </div>
                </div>
            </div>
            <div class="form-group card-body">
                <div class="row">
                    <div class="col">
                        <label for="JenjangPendidikan" class="font-weight-bold text-dark">Jenjang Pendidikan Terakhir</label>
                        <select class="form-control" id="JenjangPendidikan" name="jenjangPendidikan">
                            @if(count($dosen->pendidikan)>0)
                                <option value="" {{ $dosen->pendidikan[0]->jenjang_pendidikan_terakhir==NULL ? 'selected' : '' }}>Pilih Jenjang Pendidikan</option>
                                <option value="S1" {{ $dosen->pendidikan[0]->jenjang_pendidikan_terakhir=="S1" ? 'selected' : '' }}>S1</option>
                                <option value="S2" {{ $dosen->pendidikan[0]->jenjang_pendidikan_terakhir=="S2" ? 'selected' : '' }}>S2</option>
                                <option value="S3" {{ $dosen->pendidikan[0]->jenjang_pendidikan_terakhir=="S3" ? 'selected' : '' }}>S3</option>
                            @else
                                <option value="" selected>Pilih Jenjang Pendidikan</option>
                                <option value="S1" {{old('jenjangPendidikan')=="S1" ? 'selected' : ''}}>S1</option>
                                <option value="S2" {{old('jenjangPendidikan')=="S2" ? 'selected' : ''}}>S2</option>
                                <option value="S3" {{old('jenjangPendidikan')=="S3" ? 'selected' : ''}}>S3</option>
                            @endif
                        </select>
                        <small style="color: red">
                            @error('jenjangPendidikan')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="Institusi" class="font-weight-bold text-dark">Nama Institusi</label>
                        @if(count($dosen->pendidikan)>0)
                            <input type="text" class="form-control" id="Institusi" name="institusi" placeholder="Masukan Nama Institusi" value="{{ $errors->any() ? old('institusi') : $dosen->pendidikan[0]->nama_institusi}}">
                        @else
                            <input type="text" class="form-control" id="Institusi" name="institusi" placeholder="Masukan Nama Institusi" value="{{ $errors->any() ? old('institusi') : ''}}">
                        @endif
                        <small style="color: red">
                            @error('institusi')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="BidangIlmu" class="font-weight-bold text-dark">Bidang Ilmu</label>
                        @if(count($dosen->pendidikan)>0)
                            <input type="text" class="form-control" id="BidangIlmu" name="bidangIlmu" placeholder="Masukan Bidang Ilmu" value="{{ $errors->any() ? old('bidangIlmu') : $dosen->pendidikan[0]->bidang_ilmu}}">                            
                        @else
                            <input type="text" class="form-control" id="BidangIlmu" name="bidangIlmu" placeholder="Masukan Bidang Ilmu" value="{{ $errors->any() ? old('bidangIlmu') : ''}}">
                        @endif
                        <small style="color: red">
                            @error('bidangIlmu')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="SelesaiStudi" class="font-weight-bold text-dark">Tanggal Selesai Studi</label>
                        @if(count($dosen->pendidikan)>0)
                            <input type="date" class="form-control" id="SelesaiStudi" name="tanggalSelesaiStudi" value="{{$errors->any() ? old('tanggalSelesaiStudi') : $dosen->pendidikan[0]->tanggal_selesai_studi}}">
                        @else
                            <input type="date" class="form-control" id="SelesaiStudi" name="tanggalSelesaiStudi" value="{{$errors->any() ? old('tanggalSelesaiStudi') : ''}}">
                        @endif
                        <small style="color: red">
                            @error('tanggalSelesaiStudi')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
            </div>
    </div>
    <br>
    <div class="card shadow">
            <div class="form-group card-header shadow">
                <div class="row">
                    <div class="col col-md-12">
                        <h3 class="font-weight-bold text-primary"><i class="fas fa-level-up-alt"></i> Data Kepangkatan Fungsional</h3>
                    </div>
                </div>
            </div>
            <div class="form-group card-body">
                <div class="row">
                    <div class="col">
                        <label for="PangkatGolongan" class="font-weight-bold text-dark">Pangkat/Golongan Terakhir</label>
                        <select class="form-control" id="PangkatGolongan" name="pangkatGolongan">
                            @if(count($dosen->tmtpangkat)>0)
                                <option value="" {{ $dosen->tmtpangkat[0]->id_pangkat_pns==NULL ? 'selected' : '' }}>Pilih Pangkat/Golongan</option>
                                @foreach($pangkatDosen as $p)
                                    <option value="{{$p->id_pangkat_pns}}" {{ $dosen->tmtpangkat[0]->id_pangkat_pns==$p->id_pangkat_pns ? 'selected' : '' }}>{{$p->pangkat}}</option>
                                @endforeach
                            @else
                                <option value="" selected>Pilih Pangkat/Golongan</option>
                                @foreach($pangkatDosen as $p)
                                    <option value="{{$p->id_pangkat_pns}}" {{old('pangkatGolongan')==$p->id_pangkat_pns ? 'selected' : ''}}>{{$p->pangkat}}</option>
                                @endforeach
                            @endif
                        </select>
                        <small style="color: red">
                            @error('pangkatGolongan')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="JabatanAkademik" class="font-weight-bold text-dark">Jabatan Akademik Terakhir</label>
                        <select class="form-control" id="JabatanAkademik" name="jabatanakademik">
                            @if(count($dosen->tmtjabatan)>0)
                                <option value="" {{ $dosen->tmtjabatan[0]->id_jabatan_fungsional==NULL ? 'selected' : '' }}>Pilih Jabatan Akademik</option>
                                @foreach($jabatanDosen as $j)
                                    <option value="{{$j->id_jabatan_fungsional}}" {{ $dosen->tmtjabatan[0]->id_jabatan_fungsional==$j->id_jabatan_fungsional ? 'selected' : '' }}>{{$j->jabatan_fungsional}}</option>
                                @endforeach
                            @else
                                <option value="" selected>Pilih Jabatan Akademik</option>
                                @foreach($jabatanDosen as $j)
                                    <option value="{{$j->id_jabatan_fungsional}}" {{old('jabatanakademik')==$j->id_jabatan_fungsional ? 'selected' : ''}}>{{$j->jabatan_fungsional}}</option>
                                @endforeach
                            @endif
                        </select>
                        <small style="color: red">
                            @error('jabatanakademik')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="TmtPangkatGolongan" class="font-weight-bold text-dark">TMT Pangkat/Golongan Terakhir</label>
                        @if(count($dosen->tmtpangkat)>0)
                            <input type="date" class="form-control" id="TmtPangkatGolongan" name="tmtpangkatgolongan" value="{{$errors->any() ? old('tmtpangkatgolongan') :$dosen->tmtpangkat[0]->tmt_pangkat_golongan}}">
                        @else
                            <input type="date" class="form-control" id="TmtPangkatGolongan" name="tmtpangkatgolongan" value="{{$errors->any() ? old('tmtpangkatgolongan') :''}}">
                        @endif
                        <small style="color: red">
                            @error('tmtpangkatgolongan')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="TmtJabatan" class="font-weight-bold text-dark">TMT Jabatan Terakhir</label>
                        @if(count($dosen->tmtjabatan)>0)
                            <input type="date" class="form-control" id="TmtJabatan" name="tmtjabatan" value="{{$errors->any() ? old('tmtjabatan') :$dosen->tmtjabatan[0]->tmt_jabatan_fungsional}}">
                        @else
                            <input type="date" class="form-control" id="TmtJabatan" name="tmtjabatan" value="{{$errors->any() ? old('tmtjabatan') :''}}">
                        @endif
                        <small style="color: red">
                            @error('tmtjabatan')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
                <div class="row">
                    <div class="col col-md-6">
                        <label for="Unit" class="font-weight-bold text-dark">Unit</label>
                        <select class="form-control" id="Unit" name="unit">
                            @if(count($dosen->tmtjabatan)>0)
                                <option value="" {{ $dosen->tmtpangkat[0]->unit==NULL ? 'selected' : '' }}>Pilih Unit</option>
                                @foreach($unit as $u)
                                    <option value="{{$u->id_fakultas}}" {{ $dosen->tmtpangkat[0]->unit==$u->id_fakultas ? 'selected' : '' }}>{{$u->fakultas}}</option>
                                @endforeach
                            @else
                                <option value="" selected>Pilih Unit</option>
                                @foreach($unit as $u)
                                    <option value="{{$u->id_fakultas}}" {{old('unit')==$u->id_fakultas ? 'selected' : ''}}>{{$u->fakultas}}</option>
                                @endforeach
                            @endif
                        </select>
                        <small style="color: red">
                            @error('unit')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col col-md-6">
                        <label for="Subunit" class="font-weight-bold text-dark">Homebase</label>
                        <select class="form-control" id="Subunit" name="subunit">
                            <option value="" {{ $dosen->id_prodi==NULL ? 'selected' : '' }}>Pilih Homebase</option>
                            @if($dosen->id_prodi!=NULL)
                                @foreach($subunit as $u)
                                    <option value="{{$u->id_prodi}}" {{ $dosen->id_prodi == $u->id_prodi  ? 'selected' : '' }}>{{$u->prodi}}</option>
                                @endforeach
                            @else
                                @foreach($subunit as $u)
                                    <option value="{{$u->id_prodi}}" {{old('subunit')==$u->id_prodi ? 'selected' : ''}}>{{$u->prodi}}</option>
                                @endforeach
                            @endif
                        </select>
                        <small style="color: red">
                            @error('subunit')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
            </div>
    </div>
    <br>
    <div class="card shadow">
            <div class="form-group card-header shadow">
                <div class="row">
                    <div class="col col-md-12">
                        <h3 class="font-weight-bold text-primary"><i class="fas fa-folder-open"></i> Berkas</h3>
                    </div>
                </div>
            </div>
            <div class="form-group card-body">
                <div class="row">
                    <div class="col">
                        <label for="NoKarpeg" class="font-weight-bold text-dark">No. Karpeg</label>
                        <input type="text" class="form-control" id="NoKarpeg" name="nokarpeg" placeholder="Masukan No. Karpeg" value="{{$errors->any() ? old('nokarpeg') : $dosen->no_karpeg}}">
                        <small style="color: red">
                            @error('nokarpeg')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="FileKarpeg" class="font-weight-bold text-dark">File Karpeg</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="FileKarpeg" name="filekarpeg" onchange="this.nextElementSibling.innerText = this.files[0].name">
                            <label for="FileKarpeg" class="custom-file-label">.pdf</label>
                            <small style="color: red">
                                @error('filekarpeg')
                                    {{$message}}
                                @enderror
                            </small>
                        </div>
                        @if(!is_null($dosen->file_karpeg))
                            <a href="/admin/file/karpeg/{{$dosen->file_karpeg}}"><i class="fas fa-download"></i> Download file</a>
                        @else
                        <small style="color: red">
                            *Belum Upload File Karpeg
                        </small>
                        @endif
                    </div>
                    <div class="col">
                        <label for="NoNpwp" class="font-weight-bold text-dark">No. NPWP</label>
                        <input type="text" class="form-control" id="NoNpwp" name="nonpwp" placeholder="Masukan No. NPWP" value="{{$errors->any() ? old('nonpwp') : $dosen->no_npwp}}">
                        <small style="color: red">
                            @error('nonpwp')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="FileNpwp" class="font-weight-bold text-dark">File NPWP</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="FileNpwp" name="filenpwp" onchange="this.nextElementSibling.innerText = this.files[0].name">
                            <label for="FileNpwp" class="custom-file-label">.pdf</label>
                            <small style="color: red">
                                @error('filenpwp')
                                    {{$message}}
                                @enderror
                            </small>
                        </div>
                        @if(!is_null($dosen->file_npwp))
                            <a href="/admin/file/npwp/{{$dosen->file_npwp}}"><i class="fas fa-download"></i> Download file</a>
                        @else
                        <small style="color: red">
                            *Belum Upload File NPWP
                        </small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="NoKaris" class="font-weight-bold text-dark">No. Karis/Karsu</label>
                        <input type="text" class="form-control" id="Nokaris" name="nokaris" placeholder="Masukan No. Karis/Karsu" value="{{$errors->any() ? old('nokaris') :$dosen->no_karis_karsu}}">
                        <small style="color: red">
                            @error('nokaris')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="FileKaris" class="font-weight-bold text-dark">File Karis/Karsu</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="FileKaris" name="filekaris" onchange="this.nextElementSibling.innerText = this.files[0].name">
                            <label for="FileKaris" class="custom-file-label">.pdf</label>
                            <small style="color: red">
                                @error('filekaris')
                                    {{$message}}
                                @enderror
                            </small>
                        </div>
                        @if(!is_null($dosen->file_karis_karsu))
                            <a href="/admin/file/kariskarsu/{{$dosen->file_karis_karsu}}"><i class="fas fa-download"></i> Download file</a>
                        @else
                        <small style="color: red">
                            *Belum Upload File Karis/Karsu
                        </small>
                        @endif
                    </div>
                    <div class="col">
                        <label for="NoKtp" class="font-weight-bold text-dark">No. KTP</label>
                        <input type="text" class="form-control" id="NoKtp" name="noktp" placeholder="Masukan No. KTP" value="{{$errors->any() ? old('noktp') :$dosen->no_ktp}}">
                        <small style="color: red">
                            @error('noktp')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="FileKtp" class="font-weight-bold text-dark">File KTP</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="FileKTP" name="filektp" onchange="this.nextElementSibling.innerText = this.files[0].name">
                            <label for="FileKTP" class="custom-file-label">.pdf</label>
                            <small style="color: red">
                                @error('filektp')
                                    {{$message}}
                                @enderror
                            </small>
                        </div>
                        @if(!is_null($dosen->file_ktp))
                            <a href="/admin/file/ktp/{{$dosen->file_ktp}}"><i class="fas fa-download"></i> Download file</a>
                        @else
                        <small style="color: red">
                            *Belum Upload File KTP
                        </small>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <label for="StatusAktif" class="font-weight-bold text-dark">Status Keaktifan</label>
                        <select id="StatusAktif" class="form-control" name="statusaktif">
                            @if(count($dosen->tmtkeaktifan)>0)
                                <option value="" {{$dosen->tmtkeaktifan[0]->id_status_keaktifan==NULL ? 'selected' : '' }}>Pilih Status Keaktifan</option>
                                @foreach($statusaktif as $s)
                                    <option value="{{$s->id_status_keaktifan}}" {{$dosen->tmtkeaktifan[0]->id_status_keaktifan==$s->id_status_keaktifan ? 'selected' : '' }}>{{$s->status_keaktifan}}</option>
                                @endforeach
                            @else
                                <option value="" selected>Pilih Status Keaktifan</option>
                                @foreach($statusaktif as $s)
                                    <option value="{{$s->id_status_keaktifan}}" {{old('statusaktif')==$s->id_status_keaktifan ? 'selected' : ''}}>{{$s->status_keaktifan}}</option>
                                @endforeach
                            @endif
                        </select>
                        <small style="color: red">
                            @error('statusaktif')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class="col">
                        <label for="TmtAktif" class="font-weight-bold text-dark">TMT Keaktifan</label>
                        @if(count($dosen->tmtkeaktifan)>0)
                            <input type="date" class="form-control" id="TmtAktif" name="tmtaktif" value="{{$errors->any() ? old('tmtaktif') :$dosen->tmtkeaktifan[0]->tmt_keaktifan}}">
                        @else
                            <input type="date" class="form-control" id="TmtAktif" name="tmtaktif" value="{{$errors->any() ? old('tmtaktif') :''}}">
                        @endif
                        <small style="color: red">
                            @error('tmtaktif')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <br>
        <div class="card shadow">
            <!-- <div class="card-header">
                <h2>Data Progress Masa Studi</h2>
            </div> -->
            <div class="form-group card-header shadow">
                <div class="row">
                    <div class="col col-md-12">
                        <h3 class="font-weight-bold text-primary"><i class="fas fa-graduation-cap"></i> Data Progress Masa Studi</h3>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if($attachment == '[]')
                <div class="form-row">
                    <div class="form-group col">
                        <a style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal-global" class= "btn btn-primary text-white" id="toggle" ><i class="fas fa-download"></i> Import Attachment</a>  
                        <h4>Tidak ada Attachment</h4>
                    </div>
                </div>
                @elseif($attachment != null)
                <div class="form-row">
                <a style="margin-bottom: 10px;" data-toggle="modal" data-target="#modal-global" class= "btn btn-primary text-white" id="toggle" ><i class="fas fa-download"></i> Import Attachment</a>  
                <table id="example1" class="table table table-bordered table-striped">
                    <thead>
                        <tr align="center">
                            <th>
                                No.
                            </th>
                            <th>
                                Preview
                            </th>
                            <th>
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1?>
                            @foreach($attachment as $att)
                                <tr>
                                    <td>
                                        {{$i}}
                                    </td>
                                    <td>
                                        {{$att->file_name}}
                                    </td>
                                    <td width="20%" align="center">
                                        <div class="d-flex align-items-center">
                                            <a class= "btn btn-warning text-white mr-2 ml-2" href="{{$att->attachment}}" ><i class="fas fa-eye"></i> View</a>  
                                            <a href="{{route('admin-masa-studi-delete',encrypt($att->id))}}" class="btn btn-danger" onclick="return confirm('Delete entry?')"><i class="fa fa-trash"></i> Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php $i++ ?>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

<div class="modal fade" id="modal-global">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-body">
            <form enctype="multipart/form-data" action="{{Route('admin-masa-studi-create', $dosen->nip)}}" method="POST">
            @csrf
              <div class="text-center">
                  <i class="fa fa-3x fa-refresh fa-spin"></i>
                  <div>
                    <h4>Masukan File</h4>
                    <br>
                    <div class="file-upload-wrapper">
                        <input type="file" id="input-file-now" class="file-upload" name="attachments[]" multiple>
                        <button type="submit" class="btn btn-success">Submit</button>
                    </div>
                  </div>
              </div>
            </form>
          </div>
      </div>
  </div>
</div>
</div>
@endsection