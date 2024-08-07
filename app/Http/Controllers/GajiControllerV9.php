<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterPegawai;
use App\MasterGaji;
use App\Absensi;
use Carbon\Carbon;

class GajiControllerV9 extends Controller
{

    public function index()
    {
        $gaji = MasterGaji::with('pegawai')->get();
        return view('gaji.index', compact('gaji'));
    }
    
    // Method untuk menyimpan data gaji
    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:master_pegawai,id',
            'gaji_pokok' => 'required|numeric',
            'denda_keterlambatan' => 'required|numeric',
            'potongan_hari' => 'required|numeric',
        ]);

        // Mengambil data pegawai
        $pegawai = MasterPegawai::find($request->pegawai_id);

        // Menghitung total denda keterlambatan dan potongan hari
        $totalDendaKeterlambatan = $this->calculateDendaKeterlambatan($request->pegawai_id);
        $totalPotonganKetidakhadiran = $this->calculatePotonganKetidakhadiran($request->pegawai_id);

        // Membuat data gaji baru
        MasterGaji::create([
            'pegawai_id' => $request->pegawai_id,
            'gaji_pokok' => $request->gaji_pokok,
            'denda_keterlambatan' => $totalDendaKeterlambatan,
            'potongan_hari' => $totalPotonganKetidakhadiran,
        ]);

        return redirect()->back()->with('success', 'Data gaji berhasil disimpan.');
    }

    // Method untuk generate payroll
    public function generate(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:master_pegawai,id',
        ]);

        $pegawai = MasterPegawai::find($request->pegawai_id);

        // Menghitung total denda keterlambatan dan potongan hari
        $totalDendaKeterlambatan = $this->calculateDendaKeterlambatan($pegawai->id);
        $totalPotonganKetidakhadiran = $this->calculatePotonganKetidakhadiran($pegawai->id);

        $gajiBersih = $pegawai->gaji_pokok - $totalDendaKeterlambatan - $totalPotonganKetidakhadiran;

        $dataGaji = [
            'pegawai' => $pegawai,
            'totalDendaKeterlambatan' => $totalDendaKeterlambatan,
            'totalPotonganKetidakhadiran' => $totalPotonganKetidakhadiran,
            'gajiBersih' => $gajiBersih,
        ];

        return view('gaji.index', ['gaji' => $dataGaji]);
    }

    // Method untuk menghitung total denda keterlambatan
    protected function calculateDendaKeterlambatan($pegawaiId)
    {
        // Misal denda keterlambatan Rp.50.000 per hari keterlambatan
        $dendaPerHari = 50000;
        $absensi = Absensi::where('pegawai_id', $pegawaiId)
            ->where('jam_masuk', '>', '08:00:00')
            ->count();

        return $absensi * $dendaPerHari;
    }

    // Method untuk menghitung potongan ketidakhadiran
    protected function calculatePotonganKetidakhadiran($pegawaiId)
    {
        // Misal potongan ketidakhadiran Rp.100.000 per hari tidak hadir
        $potonganPerHari = 100000;
        $absensi = Absensi::where('pegawai_id', $pegawaiId)
            ->whereNull('jam_masuk')
            ->count();

        return $absensi * $potonganPerHari;
    }
}
