<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    public $timestamps = false;
    protected $table = 'tb_role';

    protected $fillable = ['role'];
}
