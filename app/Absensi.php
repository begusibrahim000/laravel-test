<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use Notifiable;

    protected $table = 'absensi';

    protected $fillable = [
        'pegawai_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang'
    ];

    public function pegawai()
    {
        return $this->belongsTo(MasterPegawai::class, 'pegawai_id');
    }
}
