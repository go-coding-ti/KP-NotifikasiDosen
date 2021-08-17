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

class ValidatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $data = Dosen::get();
            $prodi = Prodi::all();
            $ta = MasterTahunAjaran::all();
            return view('admin.dosen.listdosen', compact('ta','prodi','data','profiledata','role','rolepegawai'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $check = $request->session()->get('admin.id');
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $statusaktif = MasterStatusKeaktifan::all();
            $statusDosen = MasterStatusDosen::all();
            $pangkatDosen = MasterPangkatPns::all();
            $jabatanDosen = MasterJabatanFungsional::all();
            $statusKepegawaian = MasterStatusKepegawaian::all();
            $unit = Fakultas::all();
            $subunit = Prodi::all();
            // $data = Dosen::get();
            $tahun = MasterTahunAjaran::where('status','=','Aktif')->get();
            return view('admin.dosen.formdosen', compact('tahun','statusDosen', 'pangkatDosen', 'jabatanDosen', 'unit','subunit','statusaktif','statusKepegawaian','profiledata','role','rolepegawai'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $validator = Validator::make($request->all(),[
            'nidn' => 'required',
            'jenisserdos' => 'required',
            'nip' => 'required|unique:tb_dosen',
            'profile_image' => 'required',
            'nama' => 'required',
            'gelarbelakang' => 'required',
            'statusdosen' => 'required',
            'tempatlahir' => 'required',
            'tanggallahir' => 'required',
            'jeniskelamin' => 'required',
            'alamatdomisili' => 'required',
            'alamatrumah' => 'required',
            'telprumah' => 'required',
            'nohp' => 'required',
            'email' => 'required|email',
            'pangkatGolongan' => 'required',
            'jabatanakademik' => 'required',
            'tmtpangkatgolongan' => 'required',
            'tmtjabatan' => 'required',
            'unit' => 'required',
            'subunit' => 'required',
            'nokarpeg' => 'required',
            'filekarpeg' => 'required|mimetypes:application/pdf|max:8000',
            'nonpwp' => 'required',
            'filenpwp' => 'required|mimetypes:application/pdf|max:8000',
            'nokaris' => 'required',
            'filekaris' => 'required|mimetypes:application/pdf|max:8000',
            'noktp' => 'required',
            'filektp' => 'required|mimetypes:application/pdf|max:8000',
            'statusaktif' => 'required',
            'tmtaktif' => 'required',
            'jenjangPendidikan' => 'required',
            'institusi' => 'required',
            'bidangIlmu' => 'required',
            'tanggalSelesaiStudi' => 'required',
            'tmtStatusDosen' => 'required',
            'statusKepegawaian' => 'required',
            'tmtStatusKepegawaian' => 'required',
            'tahunAjaran' => 'required',
        ],$messages);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $images = null;
        $karpeg = null;
        $npwp = null;
        $karis = null;
        $ktp = null;
        $dosen = new Dosen;
        if($request->file('profile_image')){
            //simpan file
            $file = $request->file('profile_image');
            $images = time()."_".$file->getClientOriginalName();
            $dosen->foto = $images;

            $foto_upload = 'img';
            $file->move($foto_upload,$images);
        }
        if($request->file('filekarpeg')){
            //simpan file
            $file = $request->file('filekarpeg');
            $karpeg = time()."_".$file->getClientOriginalName();
            $dosen->file_karpeg = $karpeg;

            $karpeg_upload = 'karpeg';
            $file->move($karpeg_upload,$karpeg);
        }
        if($request->file('filenpwp')){
            //simpan file
            $file = $request->file('filenpwp');
            $npwp = time()."_".$file->getClientOriginalName();
            $dosen->file_npwp = $npwp;

            $npwp_upload = 'npwp';
            $file->move($npwp_upload,$npwp);
        }
        if($request->file('filekaris')){
            //simpan file
            $file = $request->file('filekaris');
            $karis = time()."_".$file->getClientOriginalName();
            $dosen->file_karis_karsu = $karis;

            $karis_upload = 'karis';
            $file->move($karis_upload,$karis);
        }
        if($request->file('filektp')){
            //simpan file
            $file = $request->file('filektp');
            $ktp = time()."_".$file->getClientOriginalName();
            $dosen->file_ktp = $ktp;

            $ktp_upload = 'ktp';
            $file->move($ktp_upload,$ktp);
        }
        $dosen->nip = $request->nip;
        $dosen->nama = $request->nama;
        $dosen->password = Hash::make($request->nip);
        $dosen->id_prodi = $request->subunit;
        $dosen->gelar_depan = $request->gelardepan;
        $dosen->gelar_belakang = $request->gelarbelakang;
        $dosen->jenis_kelamin = $request->jeniskelamin;
        $dosen->tempat_lahir = $request->tempatlahir;
        $dosen->tanggal_lahir = $request->tanggallahir;
        $dosen->alamat_domisili = $request->alamatdomisili;
        $dosen->alamat_rumah = $request->alamatrumah;
        $dosen->telp_rumah = $request->telprumah;
        $dosen->no_hp = $request->nohp;
        $dosen->email_aktif = $request->email;
        $dosen->no_karpeg = $request->nokarpeg;
        $dosen->no_npwp = $request->nonpwp;
        $dosen->no_karis_karsu = $request->nokaris;
        $dosen->no_ktp = $request->noktp;
        if($dosen->save()){
            $aktif = new MasterKeaktifan;
            $aktif->nip = $request->nip;
            $aktif->id_status_keaktifan = $request->statusaktif;
            $aktif->tmt_keaktifan = $request->tmtaktif;
            $aktif->save();
    
            $fungsi = new TmtKepangkatanFungsional;
            $fungsi->nip = $request->nip;
            $fungsi->id_pangkat_pns = $request->pangkatGolongan;
            $fungsi->unit = $request->unit;
            $fungsi->tmt_pangkat_golongan = $request->tmtpangkatgolongan;
            $fungsi->save();
    
            $jabat = new TmtJabatanFungsional;
            $jabat->id_jabatan_fungsional = $request->jabatanakademik;
            $jabat->nip = $request->nip;
            $jabat->tmt_jabatan_fungsional = $request->tmtjabatan;
            $jabat->save();
    
            $didik = new MasterIdPendidik;
            $didik->nip = $request->nip;
            $didik->jenis_id = $request->jenisserdos;
            $didik->nidn_nidk_nup = $request->nidn;
            $didik->save();
    
            $hispendik = new MasterPendidikan;
            $hispendik->nip = $request->nip;
            $hispendik->jenjang_pendidikan_terakhir=$request->jenjangPendidikan;
            $hispendik->nama_institusi = $request->institusi;
            $hispendik->bidang_ilmu = $request->bidangIlmu;
            $hispendik->tanggal_selesai_studi = $request->tanggalSelesaiStudi;
            $hispendik->save();
    
            $status = new TmtStatusDosen;
            $status->nip = $request->nip;
            $status->id_status_dosen = $request->statusdosen;
            $status->tmt_status_dosen = $request->tmtStatusDosen;
            $status->save();

            $kepeg = new TmtStatusKepegawaianDosen;
            $kepeg->id_status_kepegawaian = $request->statusKepegawaian;
            $kepeg->nip = $request->nip;
            $kepeg->tmt_status_kepegawaian_dosen = $request->tmtStatusKepegawaian;
            $kepeg->save();

            $ta = new TahunAjaranDosen;
            $ta->tahun_ajaran = $request->tahunAjaran;
            $ta->nip = $dosen->nip;
            $ta->save();
            return redirect()->route('dosen-page')->with('success','Berhasil Menambah Data Dosen!');
        }else{
            return redirect()->route('dosen-page')->with('error','Gagal Menambah Data Dosen!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $validator = Validator::make($request->all(),[
            'nidn' => 'required',
            'jenisserdos' => 'required',
            'nip' => 'required',
            'nama' => 'required',
            'gelarbelakang' => 'required',
            'statusdosen' => 'required',
            'tempatlahir' => 'required',
            'tanggallahir' => 'required',
            'jeniskelamin' => 'required',
            'alamatdomisili' => 'required',
            'alamatrumah' => 'required',
            'telprumah' => 'required',
            'nohp' => 'required',
            'email' => 'required|email',
            'pangkatGolongan' => 'required',
            'jabatanakademik' => 'required',
            'tmtpangkatgolongan' => 'required',
            'tmtjabatan' => 'required',
            'unit' => 'required',
            'subunit' => 'required',
            'nokarpeg' => 'required',
            'filekarpeg' => 'mimetypes:application/pdf|max:8000',
            'nonpwp' => 'required',
            'filenpwp' => 'mimetypes:application/pdf|max:8000',
            'nokaris' => 'required',
            'filekaris' => 'mimetypes:application/pdf|max:8000',
            'noktp' => 'required',
            'filektp' => 'mimetypes:application/pdf|max:8000',
            'statusaktif' => 'required',
            'tmtaktif' => 'required',
            'jenjangPendidikan' => 'required',
            'institusi' => 'required',
            'bidangIlmu' => 'required',
            'tanggalSelesaiStudi' => 'required',
            'tmtStatusDosen' => 'required',
            'statusKepegawaian' => 'required',
            'tmtStatusKepegawaian' => 'required',
            'tahunAjaran' => 'required',
        ],$messages);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $images = null;
        $karpeg = null;
        $npwp = null;
        $karis = null;
        $ktp = null;
        $dosen = Dosen::find($id);
        if($request->file('profile_image')){
            //simpan file
            $file = $request->file('profile_image');
            $images = time()."_".$file->getClientOriginalName();
            $dosen->foto = $images;

            $foto_upload = 'img';
            $file->move($foto_upload,$images);
        }else{
            $dosen->foto = $dosen->foto;
        }
        if($request->file('filekarpeg')){
            //simpan file
            $file = $request->file('filekarpeg');
            $karpeg = time()."_".$file->getClientOriginalName();
            $dosen->file_karpeg = $karpeg;

            $karpeg_upload = 'karpeg';
            $file->move($karpeg_upload,$karpeg);
        }else{
            $dosen->file_karpeg = $dosen->file_karpeg;
        }
        if($request->file('filenpwp')){
            //simpan file
            $file = $request->file('filenpwp');
            $npwp = time()."_".$file->getClientOriginalName();
            $dosen->file_npwp = $npwp;

            $npwp_upload = 'npwp';
            $file->move($npwp_upload,$npwp);
        }else{
            $dosen->file_npwp = $dosen->file_npwp;
        }
        if($request->file('filekaris')){
            //simpan file
            $file = $request->file('filekaris');
            $karis = time()."_".$file->getClientOriginalName();
            $dosen->file_karis_karsu = $karis;

            $karis_upload = 'karis';
            $file->move($karis_upload,$karis);
        }else{
            $dosen->file_karis_karsu = $dosen->file_karis_karsu;
        }
        if($request->file('filektp')){
            //simpan file
            $file = $request->file('filektp');
            $ktp = time()."_".$file->getClientOriginalName();
            $dosen->file_ktp = $ktp;

            $ktp_upload = 'ktp';
            $file->move($ktp_upload,$ktp);
        }else{
            $dosen->file_ktp = $dosen->file_ktp;
        }
        $dosen->nip = $request->nip;
        $dosen->nama = $request->nama;
        $dosen->id_prodi = $request->subunit;
        $dosen->gelar_depan = $request->gelardepan;
        $dosen->gelar_belakang = $request->gelarbelakang;
        $dosen->jenis_kelamin = $request->jeniskelamin;
        $dosen->tempat_lahir = $request->tempatlahir;
        $dosen->tanggal_lahir = $request->tanggallahir;
        $dosen->alamat_domisili = $request->alamatdomisili;
        $dosen->alamat_rumah = $request->alamatrumah;
        $dosen->telp_rumah = $request->telprumah;
        $dosen->no_hp = $request->nohp;
        $dosen->email_aktif = $request->email;
        $dosen->no_karpeg = $request->nokarpeg;
        $dosen->no_npwp = $request->nonpwp;
        $dosen->no_karis_karsu = $request->nokaris;
        $dosen->no_ktp = $request->noktp;
        if($dosen->update()){
            $aktif = MasterKeaktifan::where('nip','=',$dosen->nip)->first();
            if(!isset($aktif)){
                $aktifs = new MasterKeaktifan;
                $aktifs->nip = $dosen->nip;
                $aktifs->id_status_keaktifan = $request->statusaktif;
                $aktifs->tmt_keaktifan = $request->tmtaktif;
                $aktifs->save();
            }else{
                $aktif->nip = $dosen->nip;
                $aktif->id_status_keaktifan = $request->statusaktif;
                $aktif->tmt_keaktifan = $request->tmtaktif;
                $aktif->update();
            }
    
            $fungsi = TmtKepangkatanFungsional::where('nip','=',$dosen->nip)->first();
            if(!isset($fungsi)){
                $fungsis = new TmtKepangkatanFungsional;
                $fungsis->nip = $dosen->nip;
                $fungsis->id_pangkat_pns = $request->pangkatGolongan;
                $fungsis->unit = $request->unit;
                $fungsis->tmt_pangkat_golongan = $request->tmtpangkatgolongan;
                $fungsis->save();
            }else{
                $fungsi->nip = $dosen->nip;
                $fungsi->id_pangkat_pns = $request->pangkatGolongan;
                $fungsi->unit = $request->unit;
                $fungsi->tmt_pangkat_golongan = $request->tmtpangkatgolongan;
                $fungsi->update();
            }
    
            $jabat = TmtJabatanFungsional::where('nip','=',$dosen->nip)->first();
            if(!isset($jabat)){
                $jabats = new TmtJabatanFungsional;
                $jabats->id_jabatan_fungsional = $request->jabatanakademik;
                $jabats->nip = $dosen->nip;
                $jabats->tmt_jabatan_fungsional = $request->tmtjabatan;
                $jabats->save();
            }else{
                $jabat->id_jabatan_fungsional = $request->jabatanakademik;
                $jabat->nip = $dosen->nip;
                $jabat->tmt_jabatan_fungsional = $request->tmtjabatan;
                $jabat->update();
            }
    
            $didik = MasterIdPendidik::where('nip','=',$dosen->nip)->first();
            if(!isset($didik)){
                $didiks = new MasterIdPendidik;
                $didiks->nip = $dosen->nip;
                $didiks->jenis_id = $request->jenisserdos;
                $didiks->nidn_nidk_nup = $request->nidn;
                $didiks->save();
            }else{
                $didik->nip = $dosen->nip;
                $didik->jenis_id = $request->jenisserdos;
                $didik->nidn_nidk_nup = $request->nidn;
                $didik->update();
            }
    
            $hispendik = MasterPendidikan::where('nip','=',$dosen->nip)->first();
            if(!isset($hispendik)){
                $hispendiks = new MasterPendidikan;
                $hispendiks->nip = $dosen->nip;
                $hispendiks->jenjang_pendidikan_terakhir=$request->jenjangPendidikan;
                $hispendiks->nama_institusi = $request->institusi;
                $hispendiks->bidang_ilmu = $request->bidangIlmu;
                $hispendiks->tanggal_selesai_studi = $request->tanggalSelesaiStudi;
                $hispendiks->save();
            }else{
                $hispendik->nip = $dosen->nip;
                $hispendik->jenjang_pendidikan_terakhir=$request->jenjangPendidikan;
                $hispendik->nama_institusi = $request->institusi;
                $hispendik->bidang_ilmu = $request->bidangIlmu;
                $hispendik->tanggal_selesai_studi = $request->tanggalSelesaiStudi;
                $hispendik->update();
            }
    
            $status = TmtStatusDosen::where('nip','=',$dosen->nip)->first();
            if(!isset($status)){
                $statuss = new TmtStatusDosen;
                $statuss->nip = $dosen->nip;
                $statuss->id_status_dosen = $request->statusdosen;
                $statuss->tmt_status_dosen = $request->tmtStatusDosen;
                $statuss->save();
            }else{
                $status->nip = $dosen->nip;
                $status->id_status_dosen = $request->statusdosen;
                $status->tmt_status_dosen = $request->tmtStatusDosen;
                $status->update();
            }

            $kepeg = TmtStatusKepegawaianDosen::where('nip','=',$dosen->nip)->first();
            if(!isset($kepeg)){
                $kepegs = new TmtStatusKepegawaianDosen;
                $kepegs->id_status_kepegawaian = $request->statusKepegawaian;
                $kepegs->nip = $dosen->nip;
                $kepegs->tmt_status_kepegawaian_dosen = $request->tmtStatusKepegawaian;
                $kepegs->save();
            }else{
                $kepeg->id_status_kepegawaian = $request->statusKepegawaian;
                $kepeg->nip = $dosen->nip;
                $kepeg->tmt_status_kepegawaian_dosen = $request->tmtStatusKepegawaian;
                $kepeg->update();
            }

            $ta = TahunAjaranDosen::where('nip','=',$dosen->nip)->first();
            if(!isset($ta)){
                $tas = new TahunAjaranDosen;
                $tas->tahun_ajaran = $request->tahunAjaran;
                $tas->nip = $dosen->nip;
                $tas->save();
            }else{
                $ta->tahun_ajaran = $request->tahunAjaran;
                $ta->nip = $dosen->nip;
                $ta->update();
            }
            return redirect()->route('dosen-page')->with('success','Berhasil Mengupdate Data Dosen!');
        }else{
            return redirect()->route('dosen-page')->with('error','Gagal Mengupdate Data Dosen!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $dosen = Dosen::where('nip', '=', $id);
        $dosen->delete();
        return redirect()->route('dosen-page')->with('success','Berhasil Menghapus Data Dosen!');
    }

    public function detailDosen($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $dosen = Dosen::with('tmtpangkat')->where('nip','=',$id)->first();
            $check = $request->session()->get('admin.id');
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $statusaktif = MasterStatusKeaktifan::all();
            $statusDosen = MasterStatusDosen::all();
            $pangkatDosen = MasterPangkatPns::all();
            $jabatanDosen = MasterJabatanFungsional::all();
            $statusKepegawaian = MasterStatusKepegawaian::all();
            $statuskeaktifan = MasterKeaktifan::where('nip', $dosen->nip)->orderBy('tmt_keaktifan', 'DESC')->first();
            // $data = Dosen::get();
            $attachment = ProgressStudi::where('id_dosen', $dosen->nip)->orderBy('created_at')->get();
            $unit = Fakultas::all();
            $subunit = Prodi::all();
            $tahun = MasterTahunAjaran::where('status','=','Aktif')->get();
            return view('admin.dosen.formdosenedit',compact('tahun','statusDosen', 'pangkatDosen', 'jabatanDosen', 'unit','subunit','statusaktif','statusKepegawaian','profiledata','dosen', 'statuskeaktifan', 'attachment','role','rolepegawai'));
        }
    }

    public function downloadKarpeg($file){
        $file="karpeg/".$file;
        if(is_file($file)){
            $headers = array(
                'Content-Type: application/pdf',
            );
            return Response::download($file, 'Karpeg_File.pdf', $headers);
        }else{
            return redirect()->back()->with('error','Gagal Mendowload file Karpeg!');
        }
        
    }

    public function downloadKariskarsu($file){
        $file="karis/".$file;
        if(is_file($file)){
            $headers = array(
                'Content-Type: application/pdf',
            );
            return Response::download($file, 'Karis_Karsu_File.pdf', $headers);
        }else{
            return redirect()->back()->with('error','Gagal Mendowload file Karis/Karsu!');
        }
        
    }

    public function downloadNpwp($file){
        $file="npwp/".$file;
        if(is_file($file)){
            $headers = array(
                'Content-Type: application/pdf',
            );
            return Response::download($file, 'NPWP_File.pdf', $headers);
        }else{
            return redirect()->back()->with('error','Gagal Mendowload file NPWP!');
        }
        
    }

    public function downloadKtp($file){
        $file="ktp/".$file;
        if(is_file($file)){
            $headers = array(
                'Content-Type: application/pdf',
            );
            return Response::download($file, 'KTP_File.pdf', $headers);
        }else{
            return redirect()->back()->with('error','Gagal Mendowload file KTP!');
        }
    }

    public function indexFakultas(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datafakultas = Fakultas::get();
            return view('admin.masterdata.fakultas.index', compact('datafakultas','profiledata','role','rolepegawai'));
        }
    }

    public function createFakultas(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.masterdata.fakultas.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storeFakultas(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'fakultas' => 'required|unique:master_fakultas',
        ],$messages);

        $fak = new Fakultas;
        $fak->fakultas = $request->fakultas;
        $fak->save();
        return redirect()->route('masterdata-fakultas-index')->with('success','Berhasil Menambah Data Fakultas!');
    }

    public function showFakultas($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datafakultas = Fakultas::where('id_fakultas','=',$id)->first();
            return view('admin.masterdata.fakultas.edit', compact('datafakultas','profiledata','role','rolepegawai'));
        }
    }

    public function updateFakultas($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'fakultas' => 'required',
        ],$messages);

        $fak = Fakultas::find($id);
        $fak->fakultas = $request->fakultas;
        $fak->update();
        return redirect()->route('masterdata-fakultas-index')->with('success','Berhasil Mengupdate Data Fakultas!');
    }

    public function deleteFakultas($id)
    {
        $fak = Fakultas::where('id_fakultas', '=', $id);
        $fak->delete();
        return redirect()->route('masterdata-fakultas-index')->with('success','Berhasil Menghapus Data Fakultas!');
    }

    public function indexJF(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datajf = MasterJabatanFungsional::get();
            return view('admin.masterdata.jabatanfungsional.index', compact('datajf','profiledata','role','rolepegawai'));
        }
    }

    public function createJF(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.masterdata.jabatanfungsional.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storeJF(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'jabatan_fungsional' => 'required|unique:master_jabatan_fungsional',
        ],$messages);

        $jf = new MasterJabatanFungsional;
        $jf->jabatan_fungsional = $request->jabatan_fungsional;
        $jf->save();
        return redirect()->route('masterdata-jabatanfungsional-index')->with('success','Berhasil Menambah Data Jabatan Fungsional!');
    }

    public function showJF($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datajf = MasterJabatanFungsional::where('id_jabatan_fungsional','=',$id)->first();
            return view('admin.masterdata.jabatanfungsional.edit', compact('datajf','profiledata','role','rolepegawai'));
        }
    }

    public function updateJF($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'jabatan_fungsional' => 'required',
        ],$messages);

        $jf = MasterJabatanFungsional::find($id);
        $jf->jabatan_fungsional = $request->jabatan_fungsional;
        $jf->update();
        return redirect()->route('masterdata-jabatanfungsional-index')->with('success','Berhasil Mengupdate Data Jabatan Fungsional!');
    }

    public function deleteJF($id){
        $jf = MasterJabatanFungsional::where('id_jabatan_fungsional', '=', $id);
        $jf->delete();
        return redirect()->route('masterdata-jabatanfungsional-index')->with('success','Berhasil Menghapus Data Jabatan Fungsional!');
    }

    public function indexKP(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datakp = KategoriPenelitian::get();
            return view('admin.masterdata.kategoripenelitian.index', compact('datakp','profiledata','role','rolepegawai'));
        }
    }

    public function createKP(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.masterdata.kategoripenelitian.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storeKP(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'kategori_penelitian' => 'required|unique:master_kategori_penelitian',
        ],$messages);

        $kp = new KategoriPenelitian;
        $kp->kategori_penelitian = $request->kategori_penelitian;
        $kp->save();
        return redirect()->route('masterdata-kategoripenelitian-index')->with('success','Berhasil Menambah Data Kategori Penelitian!');
    }

    public function showKP($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datakp = KategoriPenelitian::where('id_kategori_penelitian','=',$id)->first();
            return view('admin.masterdata.kategoripenelitian.edit', compact('datakp','profiledata','role','rolepegawai'));
        }
    }

    public function updateKP($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'kategori_penelitian' => 'required',
        ],$messages);

        $kp = KategoriPenelitian::find($id);
        $kp->kategori_penelitian = $request->kategori_penelitian;
        $kp->update();
        return redirect()->route('masterdata-kategoripenelitian-index')->with('success','Berhasil Mengupdate Data Kategori Penelitian!');
    }

    public function deleteKP($id){
        $kp = KategoriPenelitian::where('id_kategori_penelitian', '=', $id);
        $kp->delete();
        return redirect()->route('masterdata-kategoripenelitian-index')->with('success','Berhasil Menghapus Data Kategori Penelitian!');
    }

    public function indexKPeng(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datakpeng = KategoriPengabdian::get();
            return view('admin.masterdata.kategoripengabdian.index', compact('datakpeng','profiledata','role','rolepegawai'));
        }
    }

    public function createKPeng(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.masterdata.kategoripengabdian.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storeKPeng(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'kategori_pengabdian' => 'required|unique:master_kategori_pengabdian',
        ],$messages);

        $kpeng = new KategoriPengabdian;
        $kpeng->kategori_pengabdian = $request->kategori_pengabdian;
        $kpeng->save();
        return redirect()->route('masterdata-kategoripengabdian-index')->with('success','Berhasil Menambah Data Kategori Pengabdian!');
    }

    public function showKPeng($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datakpeng = KategoriPengabdian::where('id_kategori_pengabdian','=',$id)->first();
            return view('admin.masterdata.kategoripengabdian.edit', compact('datakpeng','profiledata','role','rolepegawai'));
        }
    }

    public function updateKPeng($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'kategori_pengabdian' => 'required',
        ],$messages);

        $kpeng = KategoriPengabdian::find($id);
        $kpeng->kategori_pengabdian = $request->kategori_pengabdian;
        $kpeng->update();
        return redirect()->route('masterdata-kategoripengabdian-index')->with('success','Berhasil Mengupdate Data Kategori Pengabdian!');
    }

    public function deleteKPeng($id){
        $kpeng = KategoriPengabdian::where('id_kategori_pengabdian', '=', $id);
        $kpeng->delete();
        return redirect()->route('masterdata-kategoripengabdian-index')->with('success','Berhasil Menghapus Data Kategori Pengabdian!');
    }

    public function indexPP(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datapp = MasterPangkatPns::get();
            return view('admin.masterdata.pangkatpns.index', compact('datapp','profiledata','role','rolepegawai'));
        }
    }

    public function createPP(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.masterdata.pangkatpns.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storePP(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'pangkat' => 'required',
            'golongan' => 'required',
        ],$messages);

        $pp = new MasterPangkatPns;
        $pp->pangkat = $request->pangkat;
        $pp->golongan = $request->golongan;
        $pp->save();
        return redirect()->route('masterdata-pangkatpns-index')->with('success','Berhasil Menambah Data Pangkat PNS!');
    }

    public function showPP($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datapp = MasterPangkatPns::where('id_pangkat_pns','=',$id)->first();
            return view('admin.masterdata.pangkatpns.edit', compact('datapp','profiledata','role','rolepegawai'));
        }
    }

    public function updatePP($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'pangkat' => 'required',
            'golongan' => 'required',
        ],$messages);

        $pp = MasterPangkatPns::find($id);
        $pp->pangkat = $request->pangkat;
        $pp->golongan = $request->golongan;
        $pp->update();
        return redirect()->route('masterdata-pangkatpns-index')->with('success','Berhasil Mengupdate Data Pangkat PNS!');
    }

    public function deletePP($id){
        $pp = MasterPangkatPns::where('id_pangkat_pns', '=', $id);
        $pp->delete();
        return redirect()->route('masterdata-pangkatpns-index')->with('success','Berhasil Menghapus Data Pangkat PNS!');
    }


    public function indexProdi(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $dataprodi = Prodi::get();
            return view('admin.masterdata.prodi.index', compact('dataprodi','profiledata','role','rolepegawai'));
        }
    }

    public function createProdi(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $unit = Fakultas::all();
            return view('admin.masterdata.prodi.create', compact('unit','profiledata','role','rolepegawai'));
        }
    }

    public function storeProdi(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'id_fakultas' => 'required',
            'prodi' => 'required|unique:master_prodi',
        ],$messages);

        $prodi = new Prodi;
        $prodi->id_fakultas = $request->id_fakultas;
        $prodi->prodi = $request->prodi;
        $prodi->save();
        return redirect()->route('masterdata-prodi-index')->with('success','Berhasil Menambah Data Prodi!');
    }

    public function showProdi($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $dataprodi = Prodi::where('id_prodi','=',$id)->first();
            $unit = Fakultas::all();
            return view('admin.masterdata.prodi.edit', compact('dataprodi','unit','profiledata','role','rolepegawai'));
        }
    }

    public function updateProdi($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'id_fakultas' => 'required',
            'prodi' => 'required',
        ],$messages);

        $prodi = Prodi::find($id);
        $prodi->id_fakultas = $request->id_fakultas;
        $prodi->prodi = $request->prodi;
        $prodi->update();
        return redirect()->route('masterdata-prodi-index')->with('success','Berhasil Mengupdate Data Prodi!');
    }

    public function deleteProdi($id){
        $prodi = Prodi::where('id_prodi', '=', $id);
        $prodi->delete();
        return redirect()->route('masterdata-prodi-index')->with('success','Berhasil Menghapus Data Prodi!');
    }


    public function indexSD(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datasd = MasterStatusDosen::get();
            return view('admin.masterdata.statusdosen.index', compact('datasd','profiledata','role','rolepegawai'));
        }
    }

    public function createSD(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.masterdata.statusdosen.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storeSD(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'status_dosen' => 'required|unique:master_status_dosen',
        ],$messages);

        $sd = new MasterStatusDosen;
        $sd->status_dosen = $request->status_dosen;
        $sd->save();
        return redirect()->route('masterdata-statusdosen-index')->with('success','Berhasil Menambah Data Status Dosen!');
    }

    public function showSD($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datasd = MasterStatusDosen::where('id_status_dosen','=',$id)->first();
            return view('admin.masterdata.statusdosen.edit', compact('datasd','profiledata','role','rolepegawai'));
        }
    }

    public function updateSD($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'status_dosen' => 'required',
        ],$messages);

        $sd = MasterStatusDosen::find($id);
        $sd->status_dosen = $request->status_dosen;
        $sd->update();
        return redirect()->route('masterdata-statusdosen-index')->with('success','Berhasil Mengupdate Data Status Dosen!');
    }

    public function deleteSD($id){
        $sd = MasterStatusDosen::where('id_status_dosen', '=', $id);
        $sd->delete();
        return redirect()->route('masterdata-statusdosen-index')->with('success','Berhasil Menghapus Data Status Dosen!');
    }


    public function indexSK(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datask = MasterStatusKeaktifan::get();
            return view('admin.masterdata.statuskeaktifan.index', compact('datask','profiledata','role','rolepegawai'));
        }
    }

    public function createSK(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.masterdata.statuskeaktifan.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storeSK(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'status_keaktifan' => 'required|unique:master_status_keaktifan',
        ],$messages);

        $sk = new MasterStatusKeaktifan;
        $sk->status_keaktifan = $request->status_keaktifan;
        $sk->save();
        return redirect()->route('masterdata-statuskeaktifan-index')->with('success','Berhasil Menambah Data Status Keaktifan!');
    }

    public function showSK($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datask = MasterStatusKeaktifan::where('id_status_keaktifan','=',$id)->first();
            return view('admin.masterdata.statuskeaktifan.edit', compact('datask','profiledata','role','rolepegawai'));
        }
    }

    public function updateSK($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'status_keaktifan' => 'required',
        ],$messages);

        $sk = MasterStatusKeaktifan::find($id);
        $sk->status_keaktifan = $request->status_keaktifan;
        $sk->update();
        return redirect()->route('masterdata-statuskeaktifan-index')->with('success','Berhasil Mengupdate Data Status Keaktifan!');
    }

    public function deleteSK($id){
        $sk = MasterStatusKeaktifan::where('id_status_keaktifan', '=', $id);
        $sk->delete();
        return redirect()->route('masterdata-statuskeaktifan-index')->with('success','Berhasil Menghapus Data Status Keaktifan!');
    }


    public function indexSKp(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $dataskp = MasterStatusKepegawaian::get();
            return view('admin.masterdata.statuskepegawaian.index', compact('dataskp','profiledata','role','rolepegawai'));
        }
    }

    public function createSKp(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.masterdata.statuskepegawaian.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storeSKp(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'status_kepegawaian' => 'required|unique:master_status_kepegawaian',
        ],$messages);

        $skp = new MasterStatusKepegawaian;
        $skp->status_kepegawaian = $request->status_kepegawaian;
        $skp->save();
        return redirect()->route('masterdata-statuskepegawaian-index')->with('success','Berhasil Menambah Data Status Kepegawaian!');
    }

    public function showSKp($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $dataskp = MasterStatusKepegawaian::where('id_status_kepegawaian','=',$id)->first();
            return view('admin.masterdata.statuskepegawaian.edit', compact('dataskp','profiledata','role','rolepegawai'));
        }
    }

    public function updateSKp($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'status_kepegawaian' => 'required',
        ],$messages);

        $skp = MasterStatusKepegawaian::find($id);
        $skp->status_kepegawaian = $request->status_kepegawaian;
        $skp->update();
        return redirect()->route('masterdata-statuskepegawaian-index')->with('success','Berhasil Mengupdate Data Status Kepegawaian!');
    }

    public function deleteSKp($id){
        $skp = MasterStatusKepegawaian::where('id_status_kepegawaian', '=', $id);
        $skp->delete();
        return redirect()->route('masterdata-statuskepegawaian-index')->with('success','Berhasil Menghapus Data Status Kepegawaian!');
    }

    public function indexTAj(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datata = MasterTahunAjaran::get();
            return view('admin.masterdata.tahunajaran.index', compact('datata','profiledata','role','rolepegawai'));
        }
    }

    public function createTAj(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            return view('admin.masterdata.tahunajaran.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storeTAj(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'semester' => 'required',
            'tahunajaran' => 'required',
            'statusta' => 'required',
        ],$messages);

        $ta = new MasterTahunAjaran;
        $ta->semester = $request->semester;
        $ta->tahun_ajaran = $request->tahunajaran;
        $ta->status = $request->statusta;
        $ta->save();
        return redirect()->route('masterdata-tahunajaran-index')->with('success','Berhasil Menambah Data Tahun Ajaran!');
    }

    public function showTAj($id, Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $datata = MasterTahunAjaran::where('id','=',$id)->first();
            return view('admin.masterdata.tahunajaran.edit', compact('datata','profiledata','role','rolepegawai'));
        }
    }

    public function updateTAj($id, Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'semester' => 'required',
            'tahunajaran' => 'required',
            'statusta' => 'required',
        ],$messages);

        $ta = MasterTahunAjaran::find($id);
        $ta->semester = $request->semester;
        $ta->tahun_ajaran = $request->tahunajaran;
        $ta->status = $request->statusta;
        $ta->update();
        return redirect()->route('masterdata-tahunajaran-index')->with('success','Berhasil Mengupdate Data Tahun Ajaran!');
    }

    public function deleteTAj($id){
        $ta = MasterTahunAjaran::where('id', '=', $id);
        $ta->delete();
        return redirect()->route('masterdata-tahunajaran-index')->with('success','Berhasil Menghapus Data Tahun Ajaran!');
    }

    public function attCreate(Request $request, $id)
    {
        //
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $dosen = Dosen::with('tmtpangkat')->where('nip','=',$id)->first();
            $check = $request->session()->get('admin.id');
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            // $user = $request->session()->get('dosen.data');
            // $profiledata = Dosen::where('nip', '=', $user['nip'])->first();
            // dd($request->attachments);
            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $name = $dosen->nip.'-'.$file->getClientOriginalName();
                    $folder = 'progress-studi';
                    $file->move($folder, $name);
                    // $content = $file->getMimeType();
                    // dd($content);
                    $data[] = $name;
                    $att = new ProgressStudi;
                    $att->file_name = $name;
                    $att->attachment = '/progress-studi/'.$name;
                    $att->id_dosen = $dosen->nip;
                    $att->save();
                }
            
                // dd($id);
                $attachment = ProgressStudi::where('id_dosen', $id)->get();
                return redirect()->route('dosen-detail', $id)->withSuccess('Data Berhasil ditambahkan !');

            // return view('admin.portofolio.show', compact('portofolio', 'attachment', 'profiledata'))->with('Success!','attachment Added!');
            }
        }
    }

    public function attDestroy(Request $request, $id)
    {
        //
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $id = decrypt($id);
            $check = $request->session()->get('admin.id');
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            // $user = $request->session()->get('dosen.data');
            // $profiledata = Dosen::where('nip', '=', $user['nip'])->first();
            $statuskeaktifan = MasterKeaktifan::where('nip', $user['nip'])->orderBy('tmt_keaktifan', 'DESC')->first();
            
            $att = ProgressStudi::where('id', $id)->first();
            $dosen = Dosen::with('tmtpangkat')->where('nip','=',$att->id_dosen)->first();
            if(isset($att)){
            $att->delete();
            $attachment = ProgressStudi::where('id_dosen', $dosen->nip)->get();
            return redirect()->route('dosen-detail', $dosen->nip)->withSuccess('Data Dosen (Progress Studi) Berhasil di Hapus');

            // return view('admin.portofolio.show', compact('portofolio', 'attachment', 'profiledata'))->with('Success!','attachment Delete !');
            }else{
                return redirect()->route('dosen-detail', $dosen->nip)->withErrors('Attachment Tidak ditemukan !');
            }
        }
    }
}
