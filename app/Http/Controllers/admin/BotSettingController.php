<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\BotSetting;
use App\Pegawai;
use App\Role;
use App\RolePegawai;

class BotSettingController extends Controller
{
    public function listsetting(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $botsetting = BotSetting::all();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            return view('admin.configbot.index',compact('botsetting','profiledata', 'role', 'rolepegawai'));
        }
    }

    public function createSetting(Request $request){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();

            return view('admin.configbot.create', compact('profiledata','role','rolepegawai'));
        }
    }

    public function storeSetting(Request $request){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
            'unique' => 'Kolom :attribute Tidak Boleh Sama!',
		];

        $this->validate($request, [
            'waktu' => 'required',
            'jenis_bot' => 'required|unique:bot_setting',
            'periode' => 'required',
            'aksespetinggi' => 'required',
            'aksesadmin' => 'required',
            'akseskoprodi' => 'required',
        ],$messages);

        $bs = new BotSetting;
        $bs->jenis_bot = $request->jenis_bot;
        $bs->setting_waktu = $request->waktu;
        $bs->setting_periode = $request->periode;
        $bs->akses_petinggi = $request->aksespetinggi;
        $bs->akses_admin = $request->aksesadmin;
        $bs->akses_koprodi = $request->akseskoprodi;
        $bs->save();
        return redirect()->route('botsetting-index')->with('success','Berhasil Menambah Data Setting!');
    }

    public function showSetting(Request $request, $id){
        if(!$request->session()->has('admin')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $user = $request->session()->get('admin.data');
            $profiledata = Pegawai::where('nip','=', $user["nip"])->first();
            $role = Role::all();
            $rolepegawai = RolePegawai::all();
            $bs = BotSetting::where('id','=', $id)->first();
            return view('admin.configbot.edit', compact('profiledata','bs','role', 'rolepegawai'));
        }
    } 

    public function updateSetting(Request $request, $id){
        $messages = [
            'required' => 'Kolom :attribute Wajib Diisi!',
		];

        $this->validate($request, [
            'waktu' => 'required',
            'jenis_bot' => 'required',
            'periode' => 'required',
            'aksespetinggi' => 'required',
            'aksesadmin' => 'required',
            'akseskoprodi' => 'required',
        ],$messages);

        $bs = BotSetting::find($id);
        $bs->jenis_bot = $request->jenis_bot;
        $bs->setting_waktu = $request->waktu;
        $bs->setting_periode = $request->periode;
        $bs->akses_petinggi = $request->aksespetinggi;
        $bs->akses_admin = $request->aksesadmin;
        $bs->akses_koprodi = $request->akseskoprodi;
        $bs->update();
        return redirect()->route('botsetting-index')->with('success','Berhasil Menambah Data Setting!');
    }
}
