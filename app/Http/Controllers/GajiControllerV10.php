<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterPegawai;
use App\MasterGaji;
use App\Absensi;
use Carbon\Carbon;

class GajiControllerV10 extends Controller
{
    // Method untuk menampilkan form penghitungan gaji
    public function index()
    {
        $pegawai = MasterPegawai::all();
        return view('gaji.index', compact('pegawai'));
    }

    // Method untuk menghitung gaji
    public function calculate(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:master_pegawai,id',
            'periode' => 'required|date_format:Y-m'
        ]);

        $pegawai = MasterPegawai::find($request->pegawai_id);
        $periode = Carbon::parse($request->periode);

        // Menghitung total keterlambatan
        $totalDendaKeterlambatan = $this->calculateDendaKeterlambatan($pegawai->id, $periode);
        // Menghitung total ketidakhadiran
        $totalPotonganKetidakhadiran = $this->calculatePotonganKetidakhadiran($pegawai->id, $periode);

        $gajiBersih = $pegawai->gaji_pokok - $totalDendaKeterlambatan - $totalPotonganKetidakhadiran;

        $dataGaji = [
            'pegawai' => $pegawai,
            'totalDendaKeterlambatan' => $totalDendaKeterlambatan,
            'totalPotonganKetidakhadiran' => $totalPotonganKetidakhadiran,
            'gajiBersih' => $gajiBersih,
        ];

        return view('gaji.result', ['gaji' => $dataGaji]);
    }

    // Method untuk menghitung total denda keterlambatan
    protected function calculateDendaKeterlambatan($pegawaiId, $periode)
    {
        // Misal denda keterlambatan Rp.50.000 per 15 menit keterlambatan
        $dendaPerMenit = 50000 / 15;
        $startOfMonth = $periode->startOfMonth();
        $endOfMonth = $periode->endOfMonth();

        $absensi = Absensi::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->whereNotNull('jam_masuk')
            ->where('jam_masuk', '>', $this->getJamMasukKerja($pegawaiId))
            ->get();

        $totalDenda = 0;
        foreach ($absensi as $absen) {
            $jamMasukKerja = Carbon::parse($this->getJamMasukKerja($pegawaiId));
            $jamMasuk = Carbon::parse($absen->jam_masuk);
            $dendaMenit = $jamMasuk->diffInMinutes($jamMasukKerja);

            $totalDenda += $dendaPerMenit * $dendaMenit;
        }

        return $totalDenda;
    }

    // Method untuk menghitung total potongan ketidakhadiran
    protected function calculatePotonganKetidakhadiran($pegawaiId, $periode)
    {
        // Misal potongan ketidakhadiran Rp.100.000 per hari tidak hadir
        $potonganPerHari = 100000;
        $startOfMonth = $periode->startOfMonth();
        $endOfMonth = $periode->endOfMonth();

        $totalHadir = Absensi::where('pegawai_id', $pegawaiId)
            ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->count();

        $totalHariKerja = $periode->daysInMonth;
        $jumlahHariTidakHadir = $totalHariKerja - $totalHadir;

        return $jumlahHariTidakHadir * $potonganPerHari;
    }

    // Mendapatkan jam masuk kerja dari master hari kerja
    protected function getJamMasukKerja($pegawaiId)
    {
        $hari = Carbon::now()->format('l'); // Mendapatkan hari dalam bahasa Inggris
        $hariKerja = \App\MasterHariKerja::where('hari', $hari)->first();

        return $hariKerja ? $hariKerja->jam_masuk : '08:00:00'; // Default jam masuk jika tidak ditemukan
    }
}
