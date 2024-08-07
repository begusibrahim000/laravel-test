<?php

use Illuminate\Database\Seeder;
use App\User;
use App\MasterPegawai;
use App\MasterHariKerja;
use App\Absensi;
use Carbon\Carbon;

class UserSeederV4 extends Seeder
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
            // 'denda_keterlambatan' => 50000,
            // 'potongan_harian' => 100000,
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

        // Create sample absensi for a month
        $startOfMonth = Carbon::now()->startOfMonth();
        for ($i = 0; $i < 30; $i++) {
            $date = $startOfMonth->copy()->addDays($i)->format('Y-m-d');
            
            if ($i < 5) { // 5 days not present
                Absensi::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $date,
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                ]);
            } elseif ($i < 10) { // 5 days late
                Absensi::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $date,
                    'jam_masuk' => '08:30:00',
                    'jam_pulang' => '17:30:00',
                ]);
            } else { // 20 days present on time
                Absensi::create([
                    'pegawai_id' => $pegawai->id,
                    'tanggal' => $date,
                    'jam_masuk' => '08:00:00',
                    'jam_pulang' => '17:00:00',
                ]);
            }
        }
    }
}
