<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pegawai;
use App\Prodi;
use App\MasterTahunAjaran;
use App\Role;
use App\RolePegawai;
use Validator;
use Redirect;

class RoleSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $data = Pegawai::get();
            $prodi = Prodi::all();
            $ta = MasterTahunAjaran::all();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();


            return view('admin.role.role-index', compact('ta','prodi','data','profiledata','role', 'rolepegawai'));
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
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $validator = Validator::make($request->all(),[
            'nip' => 'required|unique:tb_dosen',
            'role' => 'required',
        ],$messages);
        
        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }
        $user = $request->session()->get('admin.data');
        $rolepegawai = new RolePegawai;
        $rolepegawai->id_role = $request->role;
        $rolepegawai->id_penambah =  $user["nip"];
        $rolepegawai->id_pengguna = $request->nip;
        $rolepegawai->save();
        return redirect()->route('roleset-index')->with('success','Berhasil Menambah Role pada Pegawai !');
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
        //
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
    }
}
