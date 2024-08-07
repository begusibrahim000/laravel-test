<?php

use Illuminate\Database\Seeder;
use App\User;
use App\MasterPegawai;
use App\MasterHariKerja;
use App\Absensi;
use Carbon\Carbon;

class UserSeederV3 extends Seeder
{
    public function run()
    {
        // Admin User
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'is_admin' => true,
        ]);

        // Master Pegawai
        $pegawai = MasterPegawai::create([
            'nama' => 'John Doe',
            'jabatan' => 'Pegawai',
            'gaji_pokok' => 3000000,
        ]);

        // Master Hari Kerja
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        foreach ($daysOfWeek as $day) {
            MasterHariKerja::create([
                'hari' => $day,
                'jam_masuk' => '08:00:00',
                'jam_keluar' => '17:00:00',
            ]);
        }

        // Create sample absensi for a week
        foreach ($daysOfWeek as $index => $day) {
            Absensi::create([
                'pegawai_id' => $pegawai->id,
                'tanggal' => Carbon::now()->startOfWeek()->addDays($index)->format('Y-m-d'),
                'jam_masuk' => '08:30:00', // sample data
                'jam_pulang' => '17:30:00', // sample data
            ]);
        }
    }
}
