<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MasterGaji extends Model
{
    use Notifiable;

    protected $table = 'master_gaji';

    protected $fillable = [
        'pegawai_id',
        'gaji_pokok',
        'denda_keterlambatan',
        'potongan_hari'
    ];

    public function pegawai()
    {
        return $this->belongsTo(MasterPegawai::class, 'pegawai_id');
    }

    public function masterPegawai()
    {
        return $this->belongsTo(MasterPegawai::class, 'pegawai_id');
    }
}
