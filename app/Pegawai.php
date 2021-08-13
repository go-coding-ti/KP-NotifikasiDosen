<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    //
    protected $table = 'tb_pegawai';

    protected $primaryKey = 'id_pegawai';

    protected $fillable = ['nama', 'gelar', 'gelar_depan', 'gelar_belakang', 'jenis_kelamin', 'tempat_lahir'
    , 'tanggal_lahir', 'alamat_domisili', 'alamat_rumah', 'telp_rumah', 'no_hp', 'email_aktif','foto', 'no_karpeg',  'file_karpeg'
    , 'no_npwp', 'file_npwp', 'no_karis/karsu', 'file_karis/karsu', 'no_ktp', 'file_ktp', 'status_keaktifan', 'tmt_keaktifan'];

    public function masteridpendidik(){
        return $this->belongsTo(MasterIdPendidik::class, 'nip');
    }

    public function masterstatusdosen(){
        return $this->belongsTo(MasterStatusDosen::class, 'id_status_dosen');
    }

    public function prodi(){
        return $this->belongsTo(Prodi::class, 'id_prodi');
    }
    public function fakultas(){
        return $this->hasOneThrough(Fakultas::class, Prodi::class);
    }

    public function pendidikan(){
        return $this->belongsTo(Pendidikan::class, 'id_pendidikan');
    }

    public function tmtpangkat(){
        return $this->hasMany(TmtKepangkatanFungsional::class, 'id_pangkat_fungsional');
    }

    public function pangkat(){
        return $this->belongsToMany('App\MasterPangkatPns', 'tmt_kepangkaktan_fungsional', 'nip', 'id_pangkat_pns')->withPivot('id_tmt_kepangkatan_fungsional');
    }

    public function role(){
        return $this->hasMany(RolePegawai::class, 'id_pengguna', 'nip');
    }

    public function roles(){
        return $this->belongsToMany(Role::class)->using(RolePegawai::class);
    }

    public function kepegawainan(){
        return $this->belongsTo(MasterStatusKepegawaian::class, 'id_status_kepegawaian');
    }

    public function tmtjabatan(){
        return $this->hasMany('App\TmtJabatanFungsional','tmt_jabatan_fungsional' , 'nip' );
    }

    public function jabatan(){
        return $this->belongsToMany('App\MasterJabatanFungsional', 'tmt_jabatan_fungsional', 'nip', 'id_jabatan_fungsional')->withPivot('id_tmt_jabatan_fungsional');
    }

    public function keaktifan(){
        return $this->hasMany('App\MasterKeaktifan', 'nip', 'id_keaktifan');
    }
}
