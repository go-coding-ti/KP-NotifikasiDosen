<?php

namespace App\Http\Controllers\export;

use App\Exports\DosenExports;
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
use App\KategoriPenelitian;
use App\Pengabdian;
use App\KategoriPengabdian;
use App\HeadingExcel;
use Illuminate\Support\Collection;
use App\Role;
use App\RolePegawai;

class ExportDosenController extends Controller
{

    public function exportDosen(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $check = $request->session()->get('admin.id');
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $dosen = Dosen::get();
            $prodi = Prodi::all();
            $header = HeadingExcel::all();
            $statusaktif = MasterStatusKeaktifan::all();
            return view('admin.dosen.exportdosen', compact('statusaktif','header','prodi','dosen','profiledata','role','rolepegawai'));
        }
    }

    public function array_remove_by_value($array, $value){
        return array_values(array_diff_key($array, array($value)));
    }
    
    public function delete_col(&$array, $key) {
        return array_walk($array, function (&$v) use ($key) {
            unset($v[$key]);
        });
    }

    public function excel(Request $request){
        $dosenArray = array();
        $dosenHead = array();
        $dosenArr = array();
        $headings = HeadingExcel::get();
        $default = array('NIDN', 'NIP', 'Nama dengan Gelar', 'Homebase');
        $condition = array_diff($request->planned_checked, $default);
        // dd($condition);
        // dd($heading->map->heading);
        $heading = $headings->map->heading->toArray(); //take all heading data from objects and make it into an array
        // dd($heading);
        if(is_null($request->row_nama)){
            return redirect()->back()->with('error', 'Tidak ada data yang dicetak!');
        }else{
            $countnip = count($request->row_nama);
            if($condition == []){
                // dd($request->planned_checked);
                for ($nips = 0; $nips < $countnip; $nips++) {
                    $dosenArray[] = array(
                        "Tahun Ajaran"=>$request->row_ta[$nips],
                        "Jenis Serdos"=>$request->row_serdos[$nips],
                        "NIDN"=>$request->row_nidn[$nips],
                        "NIP"=>$request->row_nip[$nips],
                        "Email"=>$request->row_email[$nips],
                        "Telp Rumah"=>$request->row_telprmh[$nips],
                        "No HP"=>$request->row_nohp[$nips],
                        "Nama Lengkap"=>$request->row_nama[$nips],
                        "Nama dengan Gelar"=>$request->row_namagelar[$nips],
                        "Tempat Lahir"=>$request->row_tempatlahir[$nips],
                        "Tanggal Lahir"=>$request->row_tanggallahir[$nips],
                        "Jenis Kelamin"=>$request->row_jeniskelamin[$nips],
                        "Status Dosen"=>$request->row_status[$nips],
                        "TMT Status Dosen"=>$request->row_tmtstatus[$nips],
                        "Status Kepegawaian"=>$request->row_statuskepeg[$nips],
                        "TMT Status Kepegawaian"=>$request->row_tmtkepeg[$nips],
                        "Alamat Domisili"=>$request->row_alamatdomisili[$nips],
                        "Alamat Rumah"=>$request->row_alamatrumah[$nips],
                        "Jenjang Pendidikan Terakhir"=>$request->row_pendidikanterakhir[$nips],
                        "Nama Institusi"=>$request->row_namainstitusi[$nips],
                        "Bidang Ilmu"=>$request->row_bidangilmu[$nips],
                        "Tanggal Selesai Studi"=>$request->row_selesaistudi[$nips],
                        "Pangkat/Golongan Terakhir"=>$request->row_pangkatpns[$nips],
                        "Jabatan Akademik Terakhir"=>$request->row_jabatanfungsional[$nips],
                        "TMT Pangkat/Golongan Terakhir"=>$request->row_tmtpangkatpns[$nips],
                        "TMT Jabatan Akademik Terakhir"=>$request->row_tmtjabatanfungsional[$nips],
                        "Unit"=>$request->row_fakultas[$nips],
                        "Sub Unit"=>$request->row_prodi[$nips],
                        "No Karpeg"=>$request->row_nokarpeg[$nips],
                        "File Karpeg"=>$request->row_filekarpeg[$nips],
                        "No NPWP"=>$request->row_npwp[$nips],
                        "File NPWP"=>$request->row_filenpwp[$nips],
                        "No Karis/Karsu"=>$request->row_kariskarsu[$nips],
                        "File Karis/Karsu"=>$request->row_filekariskarsu[$nips],
                        "No KTP"=>$request->row_ktp[$nips],
                        "File KTP"=>$request->row_filektp[$nips],
                        "Status Keaktifan"=>$request->row_statuskeaktifan[$nips],
                        "TMT Status Keaktifan"=>$request->row_tmtkeaktifan[$nips],
                    );
                }
                    $dosenHead[] = (
                        null
                    );
                return Excel::download(new DosenExports($dosenArray,$dosenHead), 'Dosen.xlsx');
            }else{
                $counthead = count($request->planned_checked);
                for ($nips = 0; $nips < $countnip; $nips++) {
                    $dosenArray[] = array(
                        "Tahun Ajaran"=>$request->row_ta[$nips],
                        "Jenis Serdos"=>$request->row_serdos[$nips],
                        "NIDN"=>$request->row_nidn[$nips],
                        "NIP"=>$request->row_nip[$nips],
                        "Email"=>$request->row_email[$nips],
                        "Telp Rumah"=>$request->row_telprmh[$nips],
                        "No HP"=>$request->row_nohp[$nips],
                        "Nama Lengkap"=>$request->row_nama[$nips],
                        "Nama dengan Gelar"=>$request->row_namagelar[$nips],
                        "Tempat Lahir"=>$request->row_tempatlahir[$nips],
                        "Tanggal Lahir"=>$request->row_tanggallahir[$nips],
                        "Jenis Kelamin"=>$request->row_jeniskelamin[$nips],
                        "Status Dosen"=>$request->row_status[$nips],
                        "TMT Status Dosen"=>$request->row_tmtstatus[$nips],
                        "Status Kepegawaian"=>$request->row_statuskepeg[$nips],
                        "TMT Status Kepegawaian"=>$request->row_tmtkepeg[$nips],
                        "Alamat Domisili"=>$request->row_alamatdomisili[$nips],
                        "Alamat Rumah"=>$request->row_alamatrumah[$nips],
                        "Jenjang Pendidikan Terakhir"=>$request->row_pendidikanterakhir[$nips],
                        "Nama Institusi"=>$request->row_namainstitusi[$nips],
                        "Bidang Ilmu"=>$request->row_bidangilmu[$nips],
                        "Tanggal Selesai Studi"=>$request->row_selesaistudi[$nips],
                        "Pangkat/Golongan Terakhir"=>$request->row_pangkatpns[$nips],
                        "Jabatan Akademik Terakhir"=>$request->row_jabatanfungsional[$nips],
                        "TMT Pangkat/Golongan Terakhir"=>$request->row_tmtpangkatpns[$nips],
                        "TMT Jabatan Akademik Terakhir"=>$request->row_tmtjabatanfungsional[$nips],
                        "Unit"=>$request->row_fakultas[$nips],
                        "Sub Unit"=>$request->row_prodi[$nips],
                        "No Karpeg"=>$request->row_nokarpeg[$nips],
                        "File Karpeg"=>$request->row_filekarpeg[$nips],
                        "No NPWP"=>$request->row_npwp[$nips],
                        "File NPWP"=>$request->row_filenpwp[$nips],
                        "No Karis/Karsu"=>$request->row_kariskarsu[$nips],
                        "File Karis/Karsu"=>$request->row_filekariskarsu[$nips],
                        "No KTP"=>$request->row_ktp[$nips],
                        "File KTP"=>$request->row_filektp[$nips],
                        "Status Keaktifan"=>$request->row_statuskeaktifan[$nips],
                        "TMT Status Keaktifan"=>$request->row_tmtkeaktifan[$nips],
                    );
                }
                foreach($request->planned_checked as $key=>$val) {
                    $dosenHead[] = (
                        $val
                    );
                    
                }
                $exportHead = array_diff($heading, $request->planned_checked); //selecting a value from array heading thats not in array request->planned_checked
                // dd($exportHead);
                $rows = $dosenArray;
                // $head = $dosenHead;
                $countdos = count($rows);
                // $t = array();
                // $t = $head; 
                foreach($exportHead as $key=>$val){
                    self::delete_col($dosenArray, $val);
                }
                return Excel::download(new DosenExports($dosenArray,$exportHead), 'Dosen.xlsx');
            }
        }
        
        
    }
}
