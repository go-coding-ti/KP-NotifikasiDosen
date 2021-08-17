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
use App\BotSetting;
use Carbon\Carbon;
use App\RolePegawai;

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
                $pangkatset = BotSetting::where('jenis_bot','=',1)->first();
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
                        if($pangkatset->akses_petinggi==1&&$pangkatset->akses_admin==1&&$pangkatset->akses_koprodi==1){
                            $pimpin = 
                            $not->chatid_petinggi = 1;
                            $not->chatid_admin = 1;
                            $not->chatid_koprodi = 1;
                        }elseif($pangkatset->akses_petinggi==1&&$pangkatset->akses_admin==1&&$pangkatset->akses_koprodi==0){
                            $not->chatid_petinggi = 1;
                            $not->chatid_admin = 1;
                        }elseif($pangkatset->akses_petinggi==1&&$pangkatset->akses_admin==0&&$pangkatset->akses_koprodi==0){
                            $not->chatid_petinggi = 1;
                        }elseif($pangkatset->akses_petinggi==1&&$pangkatset->akses_admin==0&&$pangkatset->akses_koprodi==1){
                            $not->chatid_petinggi = 1;
                            $not->chatid_koprodi = 1;
                        }
                        if($pangkatset->setting_periode==1){
                            $not->flag = 1;
                        }
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
                $pensiunset = BotSetting::where('jenis_bot','=',2)->first();
                foreach ($dosenb as $d){
                    $cek = NotificationPensiun::where('nip_dosen', $d['nip'])
                    ->select('nip_dosen')
                    ->first();
                    if($cek==NULL){
                        $p = new NotificationPensiun();
                        $p->nip_dosen = $d['nip'];
                        $p->chat_id = $d['chat_id'];
                        $p->cek_hari = $convert;
                        if($pensiunset->akses_petinggi==1&&$pensiunset->akses_admin==1&&$pensiunset->akses_koprodi==1){
                            $p->chatid_petinggi = 1;
                            $p->chatid_admin = 1;
                            $p->chatid_koprodi = 1;
                        }elseif($pensiunset->akses_petinggi==1&&$pensiunset->akses_admin==1&&$pensiunset->akses_koprodi==0){
                            $p->chatid_petinggi = 1;
                            $p->chatid_admin = 1;
                        }elseif($pensiunset->akses_petinggi==1&&$pensiunset->akses_admin==0&&$pensiunset->akses_koprodi==0){
                            $p->chatid_petinggi = 1;
                        }elseif($pensiunset->akses_petinggi==1&&$pensiunset->akses_admin==0&&$pensiunset->akses_koprodi==1){
                            $p->chatid_petinggi = 1;
                            $p->chatid_koprodi = 1;
                        }
                        if($pensiunset->setting_periode==1){
                            $p->flag = 1;
                        }
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
                $pensiunsset = BotSetting::where('jenis_bot','=',2)->first();
                foreach ($dosen as $d){
                    $cek = NotificationPensiun::where('nip_dosen', $d['nip'])
                    ->select('nip_dosen')
                    ->first();
                    if($cek==NULL){
                        $p = new NotificationPensiun();
                        $p->nip_dosen = $d['nip'];
                        $p->chat_id = $d['chat_id'];
                        $p->cek_hari = $convert;
                        if($pensiunsset->akses_petinggi==1&&$pensiunsset->akses_admin==1&&$pensiunsset->akses_koprodi==1){
                            $p->chatid_petinggi = 1;
                            $p->chatid_admin = 1;
                            $p->chatid_koprodi = 1;
                        }elseif($pensiunsset->akses_petinggi==1&&$pensiunsset->akses_admin==1&&$pensiunsset->akses_koprodi==0){
                            $p->chatid_petinggi = 1;
                            $p->chatid_admin = 1;
                        }elseif($pensiunsset->akses_petinggi==1&&$pensiunsset->akses_admin==0&&$pensiunsset->akses_koprodi==0){
                            $p->chatid_petinggi = 1;
                        }elseif($pensiunsset->akses_petinggi==1&&$pensiunsset->akses_admin==0&&$pensiunsset->akses_koprodi==1){
                            $p->chatid_petinggi = 1;
                            $p->chatid_koprodi = 1;
                        }
                        if($pensiunsset->setting_periode==1){
                            $p->flag = 1;
                        }
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
                $pendikset = BotSetting::where('jenis_bot','=',3)->first();
                foreach ($pendidikan as $pen){
                    $cek = NotificationPendidikan::where('nip_dosen', $pen['nip'])
                    ->select('nip_dosen')
                    ->first();
                    if($cek==NULL){
                        $pendik = new NotificationPendidikan();
                        $pendik->nip_dosen = $pen['nip'];
                        $pendik->chat_id = $pen['chat_id'];
                        $pendik->cek_hari = $convert;
                        if($pendikset->akses_petinggi==1&&$pendikset->akses_admin==1&&$pendikset->akses_koprodi==1){
                            $pendik->chatid_petinggi = 1;
                            $pendik->chatid_admin = 1;
                            $pendik->chatid_koprodi = 1;
                        }elseif($pendikset->akses_petinggi==1&&$pendikset->akses_admin==1&&$pendikset->akses_koprodi==0){
                            $pendik->chatid_petinggi = 1;
                            $pendik->chatid_admin = 1;
                        }elseif($pendikset->akses_petinggi==1&&$pendikset->akses_admin==0&&$pendikset->akses_koprodi==0){
                            $pendik->chatid_petinggi = 1;
                        }elseif($pendikset->akses_petinggi==1&&$pendikset->akses_admin==0&&$pendikset->akses_koprodi==1){
                            $pendik->chatid_petinggi = 1;
                            $pendik->chatid_koprodi = 1;
                        }
                        if($pendikset->setting_periode==1){
                            $pendik->flag = 1;
                        }
                        $pendik->save();
                        $this->info("Masuk");
                    }else{
                        $this->info("Tidak Masuk");
                    }
                }
            }
            
    }
}
