<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Pegawai;
use App\Dosen;
use App\ProgressStudi;
use App\MasterKeaktifan;


class MasaStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        if(!$request->session()->has('dosen')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $user = $request->session()->get('dosen.data');
            // dd($user);
            $profiledata = Dosen::where('nip','=', $user["nip"])->first();
            $statuskeaktifan = MasterKeaktifan::where('nip', $user['nip'])->orderBy('tmt_keaktifan', 'DESC')->first();
            // $data = Dosen::get();
            $attachment = ProgressStudi::where('id_dosen', $user["nip"])->orderBy('created_at')->get();
            // dd($attachment);
            return view('user.masa-studi.index',compact( 'profiledata', 'attachment', 'statuskeaktifan'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
        if(!$request->session()->has('dosen')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $user = $request->session()->get('dosen.data');
            $profiledata = Dosen::where('nip', '=', $user['nip'])->first();
            // dd($request->attachments);
            if ($request->hasfile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $name = $user['nip'].'-'.$file->getClientOriginalName();
                    $folder = 'progress-studi';
                    $file->move($folder, $name);
                    // $content = $file->getMimeType();
                    // dd($file->getClientMimeType());
                    $data[] = $name;
                    $att = new ProgressStudi;
                    $att->file_name = $name;
                    $att->attachment = '/progress-studi/'.$name;
                    $att->id_dosen = $user['nip'];
                    $att->save();
                }
            
                // dd($id);
                $attachment = ProgressStudi::where('id_dosen', $user['nip'])->get();
                return redirect()->route('masa-studi-index')->withSuccess('Data Berhasil ditambahkan !');

            // return view('admin.portofolio.show', compact('portofolio', 'attachment', 'profiledata'))->with('Success!','attachment Added!');
            }
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
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
    public function destroy(Request $request, $id)
    {
        //
        if(!$request->session()->has('dosen')){
            return redirect('/login')->with('expired','Session Telah Berakhir');
        }else{
            $user = $request->session()->get('dosen.data');
            $profiledata = Dosen::where('nip', '=', $user['nip'])->first();
            $statuskeaktifan = MasterKeaktifan::where('nip', $user['nip'])->orderBy('tmt_keaktifan', 'DESC')->first();
            $id = decrypt($id);
            
            $att = ProgressStudi::where('id', $id)->first();
            if(isset($att)){
            $att->delete();
            $attachment = ProgressStudi::where('id_dosen', $user['nip'])->get();
            return redirect()->route('masa-studi-index')->withSuccess('Portofolio Berhasil diubah');

            // return view('admin.portofolio.show', compact('portofolio', 'attachment', 'profiledata'))->with('Success!','attachment Delete !');
            }else{
                return redirect()->route('masa-studi-index')->withErrors('Portofolio Tidak ditemukan !');
            }
        }
    }
}
