<?php

use Illuminate\Database\Seeder;
use App\User;
use App\MasterPegawai;
use App\MasterHariKerja;
use App\Absensi;
use App\MasterGaji; // Pastikan ini di-import
use Carbon\Carbon;

class UserSeederV6 extends Seeder
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

        // Create sample pegawai
        $pegawai = MasterPegawai::create([
            'nama' => 'John Doe',
            'jabatan' => 'Staff',
            'gaji_pokok' => 3000000,
        ]);

        // Create sample absensi
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
