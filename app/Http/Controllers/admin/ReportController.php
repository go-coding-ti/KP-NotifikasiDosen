<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;
use Redirect;
use App\Dosen;
use App\ImportDosen;
use App\ImportPegawai;
use App\Pegawai;
use App\MasterIdPendidik;
use App\TmtJabatanFungsional;
use App\TmtKepangkatanFungsional;
use App\MasterStatusDosen;
use App\MasterJabatanFungsional;
use App\MasterPangkatPns;
use App\MasterPendidikan;
use App\Prodi;
use App\Fakultas;
use App\MasterStatusKeaktifan;
use App\MasterStatusKepegawaian;
use App\MasterKeaktifan;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DosenImports;
use Response;
use Illuminate\Support\Facades\Hash;;
use App\TmtStatusDosen;
use App\TmtStatusKepegawaianDosen;
use App\KategoriPenelitian;
use App\Pengabdian;
use App\KategoriPengabdian;
use App\MasterTahunAjaran;
use App\TahunAjaranDosen;
use App\ProgressStudi;
use App\Role;
use App\RolePegawai;

class ReportController extends Controller
{
    public function reportPage(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            return view('admin.report.report',compact('profiledata','role','rolepegawai'));
        }
    }
}
