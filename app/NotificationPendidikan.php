<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationPendidikan extends Model
{
    protected $table = 'notification_table_pendidikan';

    protected $fillable = ['nip_dosen','cek_hari', 'chat_id'];
}
