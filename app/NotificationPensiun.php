<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NotificationPensiun extends Model
{
    protected $table = 'notification_table_pensiun';

    protected $fillable = ['nip_dosen','cek_hari', 'chat_id'];
}
