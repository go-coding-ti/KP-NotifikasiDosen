<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\KategoriPenelitian;
use App\Dosen;
use App\Pegawai;
use App\Penelitian;
use App\Penulis;
use App\DetailPenelitian;
use App\MasterTahunAjaran;
use App\Role;
use App\RolePegawai;

class PenelitianController extends Controller
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
            $kategori = KategoriPenelitian::all();
            $datapenelitian = Penelitian::all();
            $tahunajaran = MasterTahunAjaran::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $data = Dosen::get();
            return view('admin.penelitian.penelitian', compact('kategori', 'datapenelitian', 'tahunajaran', 'data', 'profiledata', 'role', 'rolepegawai'));
        }
        
        // $id = $kategori->id_kategori_penelitian;
        // dd($kategori->id_kategori_penelitian);
    }

    public function detail(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $kategori = KategoriPenelitian::all();
            $datapenelitian = Penelitian::where('id_penelitian', $request->id)->first();
            $idpenulis = DetailPenelitian::where('id_penelitian', $request->id)->orderBy('penulis_ke', 'asc')->get();
            $tahunajaran = MasterTahunAjaran::where('id', $datapenelitian->tahun_ajaran)->first();
            $alltahun = MasterTahunAjaran::all();
            $num = 0; 
            if($idpenulis != null){
                foreach($idpenulis as $i){
                    if($i->penulis_ke != null){
                        $penulis[$num] = Penulis::where('id_penulis', $i->id_penulis)->first();
                        $penulis[$num]->setPenulis_ke($i->penulis_ke);
                    }
                    else{
                        $penulis[$num] = Penulis::where('id_penulis', $i->id_penulis)->first();
                    }
                    // dd($num);
                    $num+=1;
                }
            }
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $data = Dosen::get();
            return view('admin.penelitian.penelitian-detail', compact('kategori', 'penulis', 'datapenelitian', 'data', 'profiledata', 'tahunajaran', 'alltahun', 'role', 'rolepegawai'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request)
    {
        //update data penelitian
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $kategori = KategoriPenelitian::all();
            $datapenelitian = Penelitian::where('id_penelitian', $request->id)->first();
            $datapenelitian->judul = $request->judul;
            $datapenelitian->tahun_ajaran = $request->tahunajaran;
            $datapenelitian->edisi = $request->edisi;
            $datapenelitian->penerbit = $request->penerbit;
            $datapenelitian->tahun_publikasi = $request->tahun;
            $datapenelitian->bulan_publikasi = $request->bulan;
            $datapenelitian->keterangan = $request->keterangan;
            $datapenelitian->status_validitas = $request->statval;
            $datapenelitian->jumlah_halaman = $request->jumhal;
            $datapenelitian->isbn = $request->isbn;
            $datapenelitian->file_sk_tugas = $request->filesktugas;
            $datapenelitian->file_bukti_kerja = $request->filebuktikerja;
            $datapenelitian->file_1 = $request->file1;
            $datapenelitian->file_2 = $request->file2;
            $datapenelitian->save();
            return redirect()->route('penelitian-detail', $request->id)->with('success','Berhasil Merubah Data Penelitian !');
            // $user = $request->session()->get('admin.data');
            // $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            // $data = Dosen::get();
            // return view('admin.penelitian.penelitian-detail', compact('kategori', 'penulis', 'datapenelitian', 'data', 'profiledata', 'tahunajaran', 'alltahun'));
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
        //
        $detailpenelitian = DetailPenelitian::where('id_penelitian', $id);
        $detailpenelitian->delete();
        $penelitian = Penelitian::where('id_penelitian', '=', $id);
        $penelitian->delete();
        return redirect()->route('penelitian-list')->with('success','Berhasil Menghapus Data Fakultas!');
    }
}
