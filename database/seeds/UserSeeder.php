<?php

// BERHASIL

use Illuminate\Database\Seeder;
use App\User;
use App\MasterPegawai;
use App\MasterHariKerja;
use App\Absensi;
use App\MasterGaji;
use Carbon\Carbon;

class UserSeeder extends Seeder
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
        $pegawai1 = MasterPegawai::create([
            'nama' => 'John Doe',
            'jabatan' => 'Staff',
            'gaji_pokok' => 3000000,
        ]);

        $pegawai2 = MasterPegawai::create([
            'nama' => 'Jane Smith',
            'jabatan' => 'Staff',
            'gaji_pokok' => 3000000,
        ]);

        // Seed with some example data for pegawai1
        MasterGaji::create([
            'pegawai_id' => $pegawai1->id,
            'gaji_pokok' => 3000000,
            'denda_keterlambatan' => 10000,
            'potongan_hari' => 50000,
        ]);

        // Seed with some example data for pegawai2
        MasterGaji::create([
            'pegawai_id' => $pegawai2->id,
            'gaji_pokok' => 3000000,
            'denda_keterlambatan' => 10000,
            'potongan_hari' => 50000,
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

        // Create sample absensi for pegawai1
        $startOfMonth = Carbon::now()->startOfMonth();
        for ($i = 0; $i < 30; $i++) {
            $date = $startOfMonth->copy()->addDays($i)->format('Y-m-d');

            if ($i < 5) { // 5 days not present
                Absensi::create([
                    'pegawai_id' => $pegawai1->id,
                    'tanggal' => $date,
                    'jam_masuk' => null,
                    'jam_pulang' => null,
                ]);
            } elseif ($i < 10) { // 5 days late
                Absensi::create([
                    'pegawai_id' => $pegawai1->id,
                    'tanggal' => $date,
                    'jam_masuk' => '08:30:00',
                    'jam_pulang' => '17:30:00',
                ]);
            } else { // 20 days present on time
                Absensi::create([
                    'pegawai_id' => $pegawai1->id,
                    'tanggal' => $date,
                    'jam_masuk' => '08:00:00',
                    'jam_pulang' => '17:00:00',
                ]);
            }
        }

        // Create sample absensi for pegawai2 (always on time)
        for ($i = 0; $i < 30; $i++) {
            $date = $startOfMonth->copy()->addDays($i)->format('Y-m-d');
            Absensi::create([
                'pegawai_id' => $pegawai2->id,
                'tanggal' => $date,
                'jam_masuk' => '08:00:00',
                'jam_pulang' => '17:00:00',
            ]);
        }
    }
}
