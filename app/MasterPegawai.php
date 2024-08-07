<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class MasterPegawai extends Model
{
    use Notifiable;

    protected $table = 'master_pegawai';

    protected $fillable = [
        'nama',
        'jabatan',
        'gaji_pokok',

        'denda_keterlambatan',
        'potongan_harian'
    ];

    public function gaji()
    {
        return $this->hasOne(MasterGaji::class, 'pegawai_id');
    }

    public function masterGaji()
    {
        return $this->hasOne(MasterGaji::class, 'pegawai_id');
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'pegawai_id');
    }
}
