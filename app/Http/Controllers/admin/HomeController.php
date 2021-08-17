<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Dosen;
use App\Pegawai;
use App\Role;
use App\RolePegawai;

class HomeController extends Controller
{
    public function home(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $data = Dosen::get();
            return view('admin.homeadmin', compact('data','profiledata', 'role', 'rolepegawai'));
        }
    }
}
