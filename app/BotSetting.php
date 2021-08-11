<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BotSetting extends Model
{
    public $timestamps = false;

    protected $table = 'bot_setting';

    protected $fillable = ['jenis_bot', 'setting_periode','setting_waktu','akses_petinggi','akses_admin','akses_koprodi'];
}
