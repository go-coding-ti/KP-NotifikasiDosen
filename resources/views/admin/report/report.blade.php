@extends('adminlayout.layout')
@section('content')
@section('add_js')
<script>
$('input[type="file"]').change(function(e){
    var fileName = e.target.files[0].name;
    $('.custom-file-label').html(fileName);
});
</script>
<script>
function myFunction() {
    var x = document.getElementById("myDIV");
    if (x.style.display === "none") {
    x.style.display = "block";
    } else {
    x.style.display = "none";
    }
}
</script>
<script>
    $(document).ready(function() {
    $('#sub').click( function() {
        var data = $('input').serialize();
        var head = $('input[type="checkbox"]').serialize();
        $.ajaxSetup({
            header:$('meta[name="_token"]').attr('content')
        })e.preventDefault(e);

        $.ajax({
            type:"POST",
            url:'/admin/datauser/dosen/export/dosen/submit',
            data:data,head,
            success: function(data){
                console.log(data);
            },error: function(data){

            }
        })
    } );
    
});
</script>
<script>
    $(document).ready(function() {
    var dt = $('#example').DataTable({
        // scrollY:        200,
        // scrollCollapse: true,
        paging:         true,
        searchPanes: {
            clear: false,
            viewTotal: true,
            columns: [37, 28]
        },
        dom: 'Plfrtip',
        columnDefs: [
            {
                orderable: false,
                searchPanes: {
                    show: true,
                    options: [
                        @foreach ($statusaktif as $aktif)
                        {
                            label: '{{$aktif->status_keaktifan}}',
                            value: function(rowData, rowIdx) {
                                return rowData[37].match('{{$aktif->status_keaktifan}}');
                            }
                        },
                        @endforeach
                    ]
                },
                targets: [37]
            },
            {
                searchPanes: {
                    show: true,
                    options: [
                        @foreach ($prodi as $prodis)
                        {
                            label: '{{$prodis->prodi}}',
                            value: function(rowData, rowIdx) {
                                return rowData[28].match('{{$prodis->prodi}}');
                            }
                        },
                        @endforeach
                    ]
                },
                targets: [28]
            },
        ],
        
        order: [[ 1, 'desc' ]]
    });
    dt.searchPanes.container().prependTo(dt.table().container());
    dt.searchPanes.resizePanes();
    dt.searchPanes.container().hide();
    $('#toggle').on('click', function () {
        dt.searchPanes.container().toggle();
    });
    // $("input.planned_checked").change(function(e) {
    //     e.preventDefault();
    //     // Get the column API object
    //     var column = dt.column( $(this).attr('data-column') );
    //     // Toggle the visibility
    //     column.visible( ! column.visible() );
    // } );
});
</script>
@endsection
    <!-- Begin Page Content -->
    <div class="container-fluid">
        <div style="margin-left: 10px;" class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-list"></i> Eksport Data Dosen</h1>
        </div>
            <!-- DataTales Example -->
            <!-- Copy drisini -->
            <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Eksport Form Data Dosen</h6>
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
                <form enctype="multipart/form-data" action="/admin/datauser/dosen/export/dosen/submit" method="POST">
                @csrf
                @if($dosen == NULL)
                    <button style="margin-bottom: 10px" id="sub" class="btn btn-success" type="submit" disabled><i class="fas fa-download"></i> Export Data Dosen (.xlsx)</button></th>
                @else
                    <button style="margin-bottom: 10px" id="sub" class="btn btn-success" type="submit"><i class="fas fa-download"></i> Export Data Dosen (.xlsx)</button></th>
                @endif
                <a style="margin-bottom: 10px;" class="btn btn-info dropdown-toggle text-white" onclick="myFunction()"><i class="fas fa-print"></i> Opsi Cetak</a>
                <a style="margin-bottom: 10px;" class= "btn btn-warning dropdown-toggle text-white" id="toggle" ><i class="fas fa-search"></i> Advanced Search</a>
                <div id="myDIV" style="display: none">
                    <div style="overflow: scroll;">
                        <table class="table table-bordered" id="dataTables" cellspacing="0">
                            <thead>
                                <tr>
                                    <th colspan="{{count($header)}}">Centang Opsi Dibawah untuk Memilih Kolom yang Ingin Dicetak</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                 $i = 0;
                                 $j = $i;
                                ?>
                                <tr class="row">
                                @foreach ($header as $head)
                                    @if ($i == $j+7)
                                        @if($i == 2 || $i == 3 || $i == 8 || $i == 27)
                                            <tr class="row">
                                                <td class="col pl-4">
                                                    <label><input type="checkbox" value="{{$head->heading}}" name="planned_checked[]" class="planned_checked" data-column="{{$loop->iteration}}" checked><br>{{$head->heading}}</label> 
                                                </td>
                                        @else
                                            <tr class="row">
                                                <td class="col pl-4">
                                                    <label><input type="checkbox" value="{{$head->heading}}" name="planned_checked[]" class="planned_checked" data-column="{{$loop->iteration}}"><br>{{$head->heading}}</label> 
                                                </td>
                                        @endif
                                                <?php
                                                $j = $i;
                                                $i++ ;?>
                                    @else
                                        @if($i == 2 || $i == 3 || $i == 8 || $i == 27)
                                            <td class="col pl-4">
                                                <label><input type="checkbox" value="{{$head->heading}}" name="planned_checked[]" class="planned_checked" data-column="{{$loop->iteration}}" checked><br>{{$head->heading}}</label> 
                                            </td>
                                        @else
                                            <td class="col pl-4">
                                                <label><input type="checkbox" value="{{$head->heading}}" name="planned_checked[]" class="planned_checked" data-column="{{$loop->iteration}}"><br>{{$head->heading}}</label> 
                                            </td>
                                        @endif
                                        <?php $i++; ?>
                                    @endif  
                                @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <br>
                <table class="table table-bordered" id="example" cellspacing="0">
                    <thead>
                    <tr>
                        <th>Action</th>
                        @foreach($header as $head)
                        <th>{{$head->heading}}</th>
                        @endforeach
                    </tr>
                    </thead>
                    <tbody>
                        @if($dosen != NULL)
                        @foreach($dosen as $data)
                        <tr>
                        <td align="center">
                            <a class="btn btn-danger btn-sm" id='del' onclick="var table = $('#example').DataTable();table.row($(this).parents('tr')).remove().draw();">
                                <i style="color:#fff" class="fas fa-trash"></i>
                            </a>
                            </td>
                            <td>
                                @foreach ($data->tahunajaran as $index=>$tahunajarandosen)
                                    @if ($loop->last)
                                        {{ $tahunajarandosen->ta->semester}} - {{ $tahunajarandosen->ta->tahun_ajaran}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-nip" name="row_ta[]" 
                                @foreach ($data->tahunajaran as $index=>$tahunajarandosen)
                                    @if ($loop->last)
                                    @php $test = $tahunajarandosen->ta->semester. "-" .$tahunajarandosen->ta->tahun_ajaran @endphp
                                        value="{{$test}}"
                                    @endif
                                @endforeach>
                            </td>
                            <td>
                                @foreach ($data->masteridpendidik as $index=>$serdos)
                                    @if ($loop->last)
                                        {{ $serdos->jenis_id}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-serdos" name="row_serdos[]" 
                                @foreach ($data->masteridpendidik as $index=>$serdos)
                                    @if ($loop->last)
                                    @php $test = $serdos->jenis_id @endphp
                                        value="{{$test}}"
                                    @endif
                                @endforeach>
                            </td>
                            <td>
                                @foreach ($data->masteridpendidik as $index=>$nidn)
                                    @if ($loop->last)
                                        {{ $nidn->nidn_nidk_nup}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-nidn" name="row_nidn[]" 
                                @foreach ($data->masteridpendidik as $index=>$serdos)
                                    @if ($loop->last)
                                    @php $test = $serdos->nidn_nidk_nup @endphp
                                        value="{{$test}}"
                                    @endif
                                @endforeach>
                            </td>
                            <td>
                                {{$data->nip}}
                                <input type="hidden" id="row-1-nip" name="row_nip[]" value="{{$data->nip}}">
                            </td>
                            <td>
                                {{$data->email_aktif}}
                                <input type="hidden" id="row-1-email" name="row_email[]" value="{{$data->email_aktif}}">
                            </td>
                            <td>
                                {{$data->telp_rumah}}
                                <input type="hidden" id="row-1-telprmh" name="row_telprmh[]" value="{{$data->telp_rumah}}">
                            </td>
                            <td>
                                {{$data->no_hp}}
                                <input type="hidden" id="row-1-nohp" name="row_nohp[]" value="{{$data->no_hp}}">
                            </td>
                            <td>
                                {{$data->nama}}
                                <input type="hidden" id="row-1-nama" name="row_nama[]" value="{{$data->nama}}">
                            </td>
                            <td>
                                @if(is_null($data->gelar_belakang))
                                    {{$data->gelar_depan}}. {{$data->nama}}
                                    @php $test = $data->gelar_depan.".".$data->nama @endphp
                                @elseif(is_null($data->gelar_depan))
                                    {{$data->nama}}, {{$data->gelar_belakang}}
                                    @php $test = $data->nama.",".$data->gelar_belakang @endphp
                                @else
                                    {{$data->gelar_depan}}. {{$data->nama}}, {{$data->gelar_belakang}}
                                    @php $test = $data->gelar_depan.".".$data->nama.",".$data->gelar_belakang @endphp
                                @endif
                                <input type="hidden" id="row-1-namagelar" name="row_namagelar[]" value="{{$test}}">
                            </td>
                            <td>
                                {{$data->tempat_lahir}}
                                <input type="hidden" id="row-1-tempatlahir" name="row_tempatlahir[]" value="{{$data->tempat_lahir}}">
                            </td>
                            <td>
                                {{$data->tanggal_lahir}}
                                <input type="hidden" id="row-1-tanggallahir" name="row_tanggallahir[]" value="{{$data->tanggal_lahir}}">
                            </td>
                            <td>
                                {{$data->jenis_kelamin}}
                                <input type="hidden" id="row-1-jeniskelamin" name="row_jeniskelamin[]" value="{{$data->jenis_kelamin}}">
                            </td>
                            <td>
                                @foreach ($data->tmtstatus as $index=>$status)
                                    @foreach ($status->status as $index=>$s)
                                        @if ($loop->last)
                                            {{ $s->status_dosen}}
                                        @endif
                                    @endforeach
                                @endforeach
                                <input type="hidden" id="row-1-status" name="row_status[]" 
                                @foreach ($data->tmtstatus as $index=>$status)
                                    @foreach ($status->status as $index=>$s)
                                        @if ($loop->last)
                                            @php $t = $s->status_dosen @endphp
                                        @endif
                                    @endforeach
                                @endforeach
                                value="{{$t}}">
                            </td>
                            <td>
                                @foreach ($data->tmtstatus as $index=>$tmtstatus)
                                    @if ($loop->last)
                                        {{ $tmtstatus->tmt_status_dosen}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-tmtstatus" name="row_tmtstatus[]" 
                                @foreach ($data->tmtstatus as $index=>$tmtstatus)
                                    @if ($loop->last)
                                        @php $test = $tmtstatus->tmt_status_dosen @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->tmtkepegawaian as $index=>$kepeg)
                                    @if ($loop->last)
                                        {{$kepeg->statuskepeg->status_kepegawaian}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-statuskepeg" name="row_statuskepeg[]" 
                                @foreach ($data->tmtkepegawaian as $index=>$kepeg)
                                    @if ($loop->last)
                                        @php $test = $kepeg->statuskepeg->status_kepegawaian @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->tmtkepegawaian as $index=>$kepeg)
                                    @if ($loop->last)
                                        {{$kepeg->tmt_status_kepegawaian_dosen}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-tmtkepeg" name="row_tmtkepeg[]" 
                                @foreach ($data->tmtkepegawaian as $index=>$kepeg)
                                    @if ($loop->last)
                                        @php $test = $kepeg->tmt_status_kepegawaian_dosen @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                {{$data->alamat_domisili}}
                                <input type="hidden" id="row-1-alamatdomisili" name="row_alamatdomisili[]" value="{{$data->alamat_domisili}}">
                            </td>
                            <td>
                                {{$data->alamat_rumah}}
                                <input type="hidden" id="row-1-alamatrumah" name="row_alamatrumah[]" value="{{$data->alamat_rumah}}">
                            </td>
                            <td>
                                @foreach ($data->pendidikan as $index=>$pendidikan)
                                    @if ($loop->last)
                                        {{$pendidikan->jenjang_pendidikan_terakhir}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-pendidikanterakhir" name="row_pendidikanterakhir[]" 
                                @foreach ($data->pendidikan as $index=>$pendidikan)
                                    @if ($loop->last)
                                        @php $test = $pendidikan->jenjang_pendidikan_terakhir @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->pendidikan as $index=>$pendidikan)
                                    @if ($loop->last)
                                        {{$pendidikan->nama_institusi}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-namainstitusi" name="row_namainstitusi[]" 
                                @foreach ($data->pendidikan as $index=>$pendidikan)
                                    @if ($loop->last)
                                        @php $test = $pendidikan->nama_institusi @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->pendidikan as $index=>$pendidikan)
                                    @if ($loop->last)
                                        {{$pendidikan->bidang_ilmu}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-bidangilmu" name="row_bidangilmu[]" 
                                @foreach ($data->pendidikan as $index=>$pendidikan)
                                    @if ($loop->last)
                                        @php $test = $pendidikan->bidang_ilmu @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->pendidikan as $index=>$pendidikan)
                                    @if ($loop->last)
                                        {{$pendidikan->tanggal_selesai_studi}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-selesaistudi" name="row_selesaistudi[]" 
                                @foreach ($data->pendidikan as $index=>$pendidikan)
                                    @if ($loop->last)
                                        @php $test = $pendidikan->tanggal_selesai_studi @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->tmtpangkat as $index=>$pangkat)
                                    @if ($loop->last)
                                        {{$pangkat->pangkat->pangkat}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-pangkatpns" name="row_pangkatpns[]" 
                                @foreach ($data->tmtpangkat as $index=>$pangkat)
                                    @if ($loop->last)
                                        @php $test = $pangkat->pangkat->pangkat @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->tmtjabatan as $index=>$jabatan)
                                    @if ($loop->last)
                                        {{$jabatan->pangkat->jabatan_fungsional}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-jabatanfungsional" name="row_jabatanfungsional[]" 
                                @foreach ($data->tmtjabatan as $index=>$jabatan)
                                    @if ($loop->last)
                                        @php $test = $jabatan->pangkat->jabatan_fungsional @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->tmtpangkat as $index=>$pangkat)
                                    @if ($loop->last)
                                        {{$pangkat->tmt_pangkat_golongan}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-tmtpangkatpns" name="row_tmtpangkatpns[]" 
                                @foreach ($data->tmtpangkat as $index=>$pangkat)
                                    @if ($loop->last)
                                        @php $test = $pangkat->tmt_pangkat_golongan @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->tmtjabatan as $index=>$jabatan)
                                    @if ($loop->last)
                                        {{$jabatan->tmt_jabatan_fungsional}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-tmtjabatanfungsional" name="row_tmtjabatanfungsional[]" 
                                @foreach ($data->tmtjabatan as $index=>$jabatan)
                                    @if ($loop->last)
                                        @php $test = $jabatan->tmt_jabatan_fungsional @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                {{$data->prodi->fakultas->fakultas}}
                                <input type="hidden" id="row-1-fakultas" name="row_fakultas[]" value="{{$data->prodi->fakultas->fakultas}}">
                            </td>
                            <td>
                                {{$data->prodi->prodi}}
                                <input type="hidden" id="row-1-prodi" name="row_prodi[]" value="{{$data->prodi->prodi}}">
                            </td>
                            <td>
                                {{$data->no_karpeg}}
                                <input type="hidden" id="row-1-nokarpeg" name="row_nokarpeg[]" value="{{$data->nokarpeg}}">
                            </td>
                            <td>
                                <a href="/admin/file/karpeg/{{$data->file_karpeg}}">FILE KARPEG</a>
                                @php
                                    $d = $data->file_karpeg 
                                @endphp
                                <input type="hidden" id="row-1-filekarpeg" name="row_filekarpeg[]" 
                                value='=HYPERLINK("http://127.0.0.1:8000/admin/file/karpeg/{{$d}}","FILE KARPEG")'>
                            </td>
                            <td>
                                {{$data->no_npwp}}
                                <input type="hidden" id="row-1-npwp" name="row_npwp[]" value="{{$data->no_npwp}}">
                            </td>
                            <td>
                                <a href="/admin/file/npwp/{{$data->file_npwp}}">FILE NPWP</a>
                                @php
                                    $d = $data->file_npwp 
                                @endphp
                                <input type="hidden" id="row-1-filenpwp" name="row_filenpwp[]" 
                                value='=HYPERLINK("http://127.0.0.1:8000/admin/file/npwp/{{$d}}","FILE NPWP")'>
                            </td>
                            <td>
                                {{$data->no_karis_karsu}}
                                <input type="hidden" id="row-1-kariskarsu" name="row_kariskarsu[]" value="{{$data->no_karis_karsu}}">
                            </td>
                            <td>
                                <a href="/admin/file/kariskarsu/{{$data->file_karis_karsu}}">FILE KARIS/KARSU</a>
                                @php
                                    $d = $data->file_karis_karsu 
                                @endphp
                                <input type="hidden" id="row-1-filekariskarsu" name="row_filekariskarsu[]" 
                                value='=HYPERLINK("http://127.0.0.1:8000/admin/file/kariskarsu/{{$d}}","FILE KARIS/KARSU")'>
                            </td>
                            <td>
                                {{$data->no_ktp}}
                                <input type="hidden" id="row-1-ktp" name="row_ktp[]" value="{{$data->no_ktp}}">
                            </td>
                            <td>
                                <a href="/admin/file/ktp/{{$data->file_ktp}}">FILE KTP</a>
                                @php
                                    $d = $data->file_ktp
                                @endphp
                                <input type="hidden" id="row-1-filektp" name="row_filektp[]" 
                                value='=HYPERLINK("http://127.0.0.1:8000/admin/file/ktp/{{$d}}","FILE KTP")'>
                            </td>
                            <td>
                                @foreach ($data->tmtkeaktifan as $index=>$keaktifan)
                                    @if ($loop->last)
                                        {{$keaktifan->statusKeaktifan->status_keaktifan}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-statuskeaktifan" name="row_statuskeaktifan[]" 
                                @foreach ($data->tmtkeaktifan as $index=>$keaktifan)
                                    @if ($loop->last)
                                        @php $test = $keaktifan->statusKeaktifan->status_keaktifan @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                            <td>
                                @foreach ($data->tmtkeaktifan as $index=>$keaktifan)
                                    @if ($loop->last)
                                        {{$keaktifan->tmt_keaktifan}}
                                    @endif
                                @endforeach
                                <input type="hidden" id="row-1-tmtkeaktifan" name="row_tmtkeaktifan[]" 
                                @foreach ($data->tmtkeaktifan as $index=>$keaktifan)
                                    @if ($loop->last)
                                        @php $test = $keaktifan->tmt_keaktifan @endphp
                                    @endif
                                @endforeach
                                value="{{$test}}">
                            </td>
                        </tr>
                        @endforeach
                    @else

                    @endif
                    </tbody>
                </table>
                </form>
                </div>
            </div>
            </div>
        </div>
        <!-- /.container-fluid -->
@endsection