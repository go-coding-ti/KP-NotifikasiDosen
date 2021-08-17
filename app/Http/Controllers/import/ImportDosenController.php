<?php

namespace App\Http\Controllers\import;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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
use App\MasterTahunAjaran;
use App\TahunAjaranDosen;
use App\Role;
use App\RolePegawai;

class ImportDosenController extends Controller
{
    public function importDosen(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $imports = NULL;
            $check = $request->session()->get('admin.id');
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.dosen.importdosen', compact('profiledata','imports','role','rolepegawai'));
        }
    }

    public function storeImportDosen(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $excel = null;
            if($request->file('inputfile')){
                $role = Role::all();
                $rolepegawai = RolePegawai::all();
                $user = $request->session()->get('admin.data');
                $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
                $file = $request->file('inputfile');
                $excel = time()."_".$file->getClientOriginalName();
                
                $rows = Excel::ToCollection(new DosenImports, $file);
                $imports = $rows['0'];
    
                $upload = 'excel';
                $file->move($upload,$excel);
                return view('admin.dosen.importdosen', compact('imports','profiledata','role','rolepegawai'),['success'=>'Berhasil Meload Data Excel']);
            }else{
                return redirect()->route('admin-import-dosen')->with('error','Gagal Meload Excel');
            }
        }
        
    }

    public function downloadExcelDosen(){
        $file="excel/contohdosen.xls";
        $headers = array(
            'Content-Type: application/vnd.ms-excel',
        );
        return Response::download($file, 'Contoh_File.xls', $headers);
    }

    public function storeDosenFromImport(Request $request){
        $countnip = count($request->row_nip);
        for ($nips = 0; $nips < $countnip; $nips++) {
            $checkDosen = Dosen::where('nip', $request->row_nip[$nips])->first();
            if (isset($checkDosen)) {
                $storeDosen = Dosen::find($request->row_nip[$nips]);
                $storeDosen->nip = $request->row_nip[$nips];
                $storeDosen->nama = $request->row_nama[$nips];
                $storeDosen->alamat_rumah = $request->row_alamat[$nips];
                $storeDosen->jenis_kelamin = $request->row_jeniskelamin[$nips];
                $storeDosen->tanggal_lahir = $request->row_tanggallahir[$nips];
                $storeDosen->email_aktif = $request->row_email[$nips];
                $storeDosen->no_hp = $request->row_telepon[$nips];
                $storeDosen->password = Hash::make($request->row_nip[$nips]);
                $storeDosen->update();

                $id_status_keaktifan = MasterStatusKeaktifan::where('status_keaktifan','=',$request->row_keaktifan[$nips])->first();
                $updateMK = MasterKeaktifan::where('nip','=',$request->row_nip[$nips])->first();
                $keaktifan = MasterKeaktifan::find($updateMK->id_keaktifan);
                $keaktifan->nip = $request->row_nip[$nips];
                $keaktifan->id_status_keaktifan = $id_status_keaktifan->id_status_keaktifan;
                $keaktifan->tmt_keaktifan = $request->row_tmt_keaktifan[$nips];
                $keaktifan->update();

                $updateIP = MasterIdPendidik::where('nip','=',$request->row_nip[$nips])->first();
                $nidn = MasterIdPendidik::find($updateIP->id_pendidik);
                $nidn->nip = $request->row_nip[$nips];
                $nidn->jenis_id = "NIDN";
                $nidn->nidn_nidk_nup = $request->row_nidn[$nips];
                $nidn->update();

                $jurusan = Prodi::where('prodi','=',$request->row_subunit[$nips])->first();
                $storeDosen = Dosen::find($request->row_nip[$nips]);
                $storeDosen->id_prodi = $jurusan->id_prodi;
                $storeDosen->update();

                $updateP = MasterPendidikan::where('nip','=',$request->row_nip[$nips])->first();
                $pendidikan = MasterPendidikan::find($updateP->id_pendidikan);
                $pendidikan->nip = $request->row_nip[$nips];
                $pendidikan->jenjang_pendidikan_terakhir = $request->row_pendidikan[$nips];
                $pendidikan->update();

                $updateTMTsk = TmtStatusKepegawaianDosen::where('nip','=',$request->row_nip[$nips])->first();
                $id_status_kepegawaian_dosen = MasterStatusKepegawaian::where('status_kepegawaian','=',$request->row_status[$nips])->first();
                $kepegawaian = TmtStatusKepegawaianDosen::find($updateTMTsk->id);
                $kepegawaian->nip = $request->row_nip[$nips];
                $kepegawaian->id_status_kepegawaian = $id_status_kepegawaian_dosen->id_status_kepegawaian;
                $kepegawaian->update();

                $updateTMTkf = TmtKepangkatanFungsional::where('nip','=',$request->row_nip[$nips])->first();
                $id_kepangkatan_pns = MasterPangkatPns::where('golongan','LIKE','%'.$request->row_kepangkatan[$nips].'%')->first();
                $pangkat = TmtKepangkatanFungsional::find($updateTMTkf->id_tmt_kepangkatan_fungsional);
                $pangkat->nip = $request->row_nip[$nips];
                $pangkat->id_pangkat_pns = $id_kepangkatan_pns->id_pangkat_pns;
                $unit = Fakultas::where('fakultas','=',$request->row_unit[$nips])->first();
                $pangkat->unit = $unit->id_fakultas;
                $pangkat->update();
                
                $updateTMTjf = TmtJabatanFungsional::where('nip','=',$request->row_nip[$nips])->first();
                $id_jabatan_fungsional = MasterJabatanFungsional::where('jabatan_fungsional','=',$request->row_jabatan[$nips])->first();
                $fungsional = TmtJabatanFungsional::find($updateTMTjf->id_tmt_jabatan_fungsional);
                $fungsional->nip = $request->row_nip[$nips];
                $fungsional->id_jabatan_fungsional = $id_jabatan_fungsional->id_jabatan_fungsional;
                $fungsional->update();
                
                $idstatus = TmtStatusDosen::where('nip','=',$request->row_nip[$nips])->first();
                $updateStatus = TmtStatusDosen::find($idstatus->id);
                $updateStatus->nip = $request->row_nip[$nips];
                $updateStatus->update();
                continue;
            }else if (!isset($checkDosen)) {
                $storeDosen = new Dosen;
                $storeDosen->nip = $request->row_nip[$nips];
                $storeDosen->nama = $request->row_nama[$nips];
                $storeDosen->alamat_rumah = $request->row_alamat[$nips];
                $storeDosen->jenis_kelamin = $request->row_jeniskelamin[$nips];
                $storeDosen->tanggal_lahir = $request->row_tanggallahir[$nips];
                $storeDosen->email_aktif = $request->row_email[$nips];
                $storeDosen->no_hp = $request->row_telepon[$nips];
                $storeDosen->password = Hash::make($request->row_nip[$nips]);
                $storeDosen->save();

                $id_status_keaktifan = MasterStatusKeaktifan::where('status_keaktifan','=',$request->row_keaktifan[$nips])->first();
                $keaktifan = new MasterKeaktifan;
                $keaktifan->nip = $request->row_nip[$nips];
                $keaktifan->id_status_keaktifan = $id_status_keaktifan->id_status_keaktifan;
                $keaktifan->tmt_keaktifan = $request->row_tmt_keaktifan[$nips];
                $keaktifan->save();

                $nidn = new MasterIdPendidik;
                $nidn->nip = $request->row_nip[$nips];
                $nidn->jenis_id = "NIDN";
                $nidn->nidn_nidk_nup = $request->row_nidn[$nips];
                $nidn->save();

                $jurusan = Prodi::where('prodi','=',$request->row_subunit[$nips])->first();
                $storeDosen = Dosen::find($request->row_nip[$nips]);
                $storeDosen->id_prodi = $jurusan->id_prodi;
                $storeDosen->update();

                $pendidikan = new MasterPendidikan;
                $pendidikan->nip = $request->row_nip[$nips];
                $pendidikan->jenjang_pendidikan_terakhir = $request->row_pendidikan[$nips];
                $pendidikan->save();

                $id_status_kepegawaian_dosen = MasterStatusKepegawaian::where('status_kepegawaian','=',$request->row_status[$nips])->first();
                $kepegawaian = new TmtStatusKepegawaianDosen;
                $kepegawaian->nip = $request->row_nip[$nips];
                $kepegawaian->id_status_kepegawaian = $id_status_kepegawaian_dosen->id_status_kepegawaian;
                $kepegawaian->save();

                $id_kepangkatan_pns = MasterPangkatPns::where('golongan','LIKE','%'.$request->row_kepangkatan[$nips].'%')->first();
                $pangkat = new TmtKepangkatanFungsional;
                $pangkat->nip = $request->row_nip[$nips];
                $pangkat->id_pangkat_pns = $id_kepangkatan_pns->id_pangkat_pns;
                $unit = Fakultas::where('fakultas','=',$request->row_unit[$nips])->first();
                $pangkat->unit = $unit->id_fakultas;
                $pangkat->save();
                
                $id_jabatan_fungsional = MasterJabatanFungsional::where('jabatan_fungsional','=',$request->row_jabatan[$nips])->first();
                $fungsional = new TmtJabatanFungsional;
                $fungsional->nip = $request->row_nip[$nips];
                $fungsional->id_jabatan_fungsional = $id_jabatan_fungsional->id_jabatan_fungsional;
                $fungsional->save();

                $status = new TmtStatusDosen;
                $status->nip = $request->row_nip[$nips];
                $status->save();
                // row_tahun
                // row_status_serdos
                // row_tahun_serdos
                // row_tahun_ajaran

                // $ta = new TahunAjaranDosen;
                // $ta->
            }
        }return redirect()->route('admin-import-dosen')->with('success','Berhasil Mengupload Data');
    }
}
