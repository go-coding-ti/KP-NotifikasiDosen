@extends('adminlayout.layout')
@section('content')
@section('add_js')
<script>
  $(document).ready(function() {
  var dta = $('#example').DataTable({
      scrollY:        200,
      scrollCollapse: true,
      paging:         true,
      autoWidth: false,
      searchPanes: {
          clear: false,
          viewTotal: true,
          columns: [1, 2]
      },
      dom: 'Plfrtip',
      columnDefs: [
          {
              orderable: false,
              searchPanes: {
                  show: true,
                  options: [
                    @foreach ($role as $r)
                      {
                          label: '{{$r->role}}',
                          value: function(rowData, rowIdx) {
                              return rowData[2].match('{{$r->role}}');
                          }
                      },
                      @endforeach
                  ]
              },
              targets: [2]
          },
          {
              searchPanes: {
                  show: true,
                  options: [
                      @foreach ($data as $pegawai)
                      {
                          label: '{{$pegawai->nip}}',
                          value: function(rowData, rowIdx) {
                              return rowData[1].match('{{$pegawai->nip}}');
                          }
                      },
                      @endforeach
                  ]
              },
              targets: [1]
          },
      ],
      
      order: [[ 1, 'desc' ]]
  });
  dta.searchPanes.container().prependTo(dta.table().container());
  dta.searchPanes.resizePanes();
  dta.searchPanes.container().hide();
  $('#toggles').on('click', function () {
      dta.searchPanes.container().toggle();
  });
  
});
</script>
@endsection
<!-- Begin Page Content -->
<div class="container-fluid">
    <div style="margin-left: 10px;" class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-list"></i> Manajemen Role</h1>
    </div>
        <!-- DataTales Example -->
        <!-- Copy drisini -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">List Data Pegawai</h6>
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
            <a style="margin-bottom: 10px;" class= "btn btn-warning dropdown-toggle text-white" id="toggles" ><i class="fas fa-search"></i> Advanced Search</a>
                <table class="table table-bordered" id="example" cellspacing="0">
                    <thead align="center">
                        <tr>
                            <th>Action</th>
                            <th>NIP</th>
                            <th>Role</th>
                            <th>Nama(dengan gelar)</th>
                            <th>No HP</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $i => $pegawai)
                        <tr>
                            <td width="12%" align="center">
                                <a href="" class="btn btn-warning btn-sm"><i class="fas fa-eye"></i></a>
                                <a style="margin-right:7px" class="btn btn-danger btn-sm" href="" onclick="return confirm('Apakah Anda Yakin ?')"><i class="fas fa-trash"></i></a>
                            </td>
                            <td>{{$pegawai->nip}}</td>
                        @if($pegawai->role == '[]')
                            <td>No Role</td>
                        @else
                            <!-- {{$pegawai->role->last()->id_role}} -->
                            @foreach($role as $rs)
                                @if($rs->id == $pegawai->role->last()->id_role)
                                    <td>{{$rs->role}}</td>
                                    @break
                                @endif
                            @endforeach
                        @endif
                        @if(is_null($pegawai->gelar_depan) && is_null($pegawai->gelar_belakang))
                            <td>{{$pegawai->nama}}</td>
                        @elseif(is_null($pegawai->gelar_depan))
                            <td>{{$pegawai->nama}}, {{$pegawai->gelar_belakang}}</td>
                        @else
                            <td>{{$pegawai->gelar_depan}} {{$pegawai->nama}}, {{$pegawai->gelar_belakang}}</td>
                        @endif
                            <td>{{$pegawai->no_hp}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card shadow mb-4">
        <form method="POST" enctype="multipart/form-data" action="{{route('roleset-store')}}">
        @csrf
            <div class="form-group card-header py-3">
                <div class="row">
                    <div class="col">
                        <h3 class="m-0 font-weight-bold text-primary">Tambah Role</h6>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-success " onclick="return confirm('Apakah Anda Yakin Ingin Menyimpan Data?')"><i class="fas fa-save"></i> Simpan</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group card-body">
                <div class="row">
                    <div class='col'>
                        <label for="nip" class="font-weight-bold text-dark">NIP</label>
                        <input type="text" class="form-control" id="nip" name="nip" placeholder="Masukan NIP" value="{{$errors->any() ? old('nip') : ''}}">
                        <small style="color: red">
                            @error('nip')
                                {{$message}}
                            @enderror
                        </small>
                    </div>
                    <div class='col'>
                        <label for="role" class="font-weight-bold text-dark">Jenis Role</label>
                            <select class="form-control" id="role" name="role">
                                <option value="" selected>Pilih Jenis Role</option>
                                @foreach($role as $r)
                                    <option value="{{$r->id}}" {{old('Role')=='$r->role' ? 'selected' : ''}}>{{$r->role}}</option>
                                @endforeach
                            </select>
                            <small style="color: red">
                                @error('role')
                                    {{$message}}
                                @enderror
                            </small>
                    </div>
                </div>
            </div>
        </form>
    </div>

</div>


@endsection