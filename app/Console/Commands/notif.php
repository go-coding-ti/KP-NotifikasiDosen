<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Dosen;
use App\TmtKepangkatanFungsional;
use App\MasterKeaktifan;
use App\TmtStatusDosen;
use App\Notification;
use App\NotificationPensiun;
use App\NotificationPendidikan;
use Carbon\Carbon;

class notif extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:notif';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Inserting Data';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        date_default_timezone_set("Asia/Makassar");
            //cek kepangkatan
            $tmt = Carbon::now()->subYears(2);
            $datebatas = Carbon::parse($tmt)->format('Y-m-d');
            $curdate = Carbon::now();
            $convert = Carbon::parse($curdate)->format('Y-m-d');
            $tmtdos = TmtKepangkatanFungsional::where('tmt_pangkat_golongan', $datebatas)
                    ->join('tb_dosen','tb_dosen.nip','=','tmt_kepangkatan_fungsional.nip')
                    ->select('tb_dosen.chat_id','tb_dosen.nip','tmt_kepangkatan_fungsional.id_tmt_kepangkatan_fungsional')
                    ->get();
            if(isset($tmtdos)){
                foreach ($tmtdos as $tmtd){
                    $cek = Notification::where('id_kepangkatan', $tmtd['id_tmt_kepangkatan_fungsional'])
                    ->select('nip_dosen')
                    ->first();
                    if($cek==NULL){
                        $not = new Notification();
                        $not->nip_dosen = $tmtd['nip'];
                        $not->id_kepangkatan = $tmtd['id_tmt_kepangkatan_fungsional'];
                        $not->chat_id = $tmtd['chat_id'];
                        $not->cek_hari = $convert;
                        $not->save();
                        $this->info("Masuk");
                    }else{
                        $this->info("Tidak Masuk");
                    }
                }
            }

            //cek pensiun
            $pensiunb = Carbon::now()->subYears(64);
            $datebataspensiunb = Carbon::parse($pensiunb)->format('Y-m-d');
            $dosenb = TmtStatusDosen::join('tb_dosen','tb_dosen.nip','=','tmt_status_dosen.nip')
                    ->join('master_status_dosen','master_status_dosen.id_status_dosen','=','tmt_status_dosen.id_status_dosen')
                    ->select('tb_dosen.nip','tb_dosen.chat_id')
                    ->where('tb_dosen.tanggal_lahir', $datebataspensiunb)
                    ->where('master_status_dosen.status_dosen','like', 'dosen%')
                    ->get();
            if(isset($dosenb)){
                foreach ($dosenb as $d){
                    $cek = NotificationPensiun::where('nip_dosen', $d['nip'])
                    ->select('nip_dosen')
                    ->first();
                    if($cek==NULL){
                        $p = new NotificationPensiun();
                        $p->nip_dosen = $d['nip'];
                        $p->chat_id = $d['chat_id'];
                        $p->cek_hari = $convert;
                        $p->save();
                        $this->info("Masuk");
                    }else{
                        $this->info("Tidak Masuk");
                    }
                }
            }

            //cek pensiun
            $pensiun = Carbon::now()->subYears(69);
            $datebataspensiun = Carbon::parse($pensiun)->format('Y-m-d');
            $dosen = TmtStatusDosen::join('tb_dosen','tb_dosen.nip','=','tmt_status_dosen.nip')
                    ->join('master_status_dosen','master_status_dosen.id_status_dosen','=','tmt_status_dosen.id_status_dosen')
                    ->select('tb_dosen.nip','tb_dosen.chat_id')
                    ->where('tb_dosen.tanggal_lahir', $datebataspensiun)
                    ->where('master_status_dosen.status_dosen','like', 'profesor%')
                    ->get();
            if(isset($dosen)){
                foreach ($dosen as $d){
                    $cek = NotificationPensiun::where('nip_dosen', $d['nip'])
                    ->select('nip_dosen')
                    ->first();
                    if($cek==NULL){
                        $p = new NotificationPensiun();
                        $p->nip_dosen = $d['nip'];
                        $p->chat_id = $d['chat_id'];
                        $p->cek_hari = $convert;
                        $p->save();
                        $this->info("Masuk");
                    }else{
                        $this->info("Tidak Masuk");
                    }
                }
            }

            //cek tugas belajar
            $pendidikan = MasterKeaktifan::join('tb_dosen','tb_dosen.nip','=','master_keaktifan.nip')
                    ->join('master_status_keaktifan','master_status_keaktifan.id_status_keaktifan','=','master_keaktifan.id_status_keaktifan')
                    ->select('tb_dosen.chat_id','tb_dosen.nip')
                    ->where('master_status_keaktifan.status_keaktifan', 'Tugas Belajar')
                    ->get();
            if(isset($pendidikan)){
                foreach ($pendidikan as $pen){
                    $cek = NotificationPendidikan::where('nip_dosen', $pen['nip'])
                    ->select('nip_dosen')
                    ->first();
                    if($cek==NULL){
                        $pendik = new NotificationPendidikan();
                        $pendik->nip_dosen = $pen['nip'];
                        $pendik->chat_id = $pen['chat_id'];
                        $pendik->cek_hari = $convert;
                        $pendik->save();
                        $this->info("Masuk");
                    }else{
                        $this->info("Tidak Masuk");
                    }
                }
            }
            
    }
}
