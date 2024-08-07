<?php

use Illuminate\Database\Seeder;
use App\User;
use App\MasterPegawai;
use App\MasterGaji;
use App\MasterHariKerja;
use App\Absensi;

class UserSeederV2 extends Seeder
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

        // Create sample pegawai
        // $pegawai = Pegawai::create([
        //     'nama' => 'John Doe',
        //     'jabatan' => 'Developer',
        //     'gaji_pokok' => 5000000,
        //     'denda_keterlambatan' => 100000, // Example value
        //     'potongan_harian' => 200000, // Example value
        // ]);

        // Master Gaji
        // MasterGaji::create([
        //     'pegawai_id' => $pegawai->id,
        //     'gaji_pokok' => 3000000,
        //     'denda_keterlambatan' => 50000,
        //     'potongan_hari' => 100000,
        // ]);

        // Master Hari Kerja
        MasterHariKerja::create([
            'hari' => 'Monday',
            'jam_masuk' => '08:00:00',
            'jam_keluar' => '17:00:00',
        ]);

        // Create sample absensi
        Absensi::create([
            'pegawai_id' => $pegawai->id,
            'tanggal' => now()->format('Y-m-d'),
            'jam_masuk' => '08:30:00',
            'jam_pulang' => '17:30:00',
        ]);
    }
}
