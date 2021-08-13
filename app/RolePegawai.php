<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;

class RolePegawai extends Model
{
    //
    //
    use AsPivot;
    public $timestamps = true;

    protected $table = 'tb_role_pengguna';

    protected $fillable = ['id_role','id_penamabah', 'id_pengguna'];

    public function pengguna(){
        return $this->belongsTo(Pegawai::class, 'id_pengguna');
    }

    public function penambah(){
        return $this->belongsTo(Pegawai::class, 'id_penambah');
    }

    public function roles(){
        return $this->belongsTo(RolePegawai::class,'id_role');
    }
}
