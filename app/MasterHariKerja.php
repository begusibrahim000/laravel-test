<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MasterHariKerja extends Model
{
    use Notifiable;

    protected $table = 'master_hari_kerja';

    protected $fillable = [
        'hari',
        'jam_masuk',
        'jam_keluar'
    ];
}
