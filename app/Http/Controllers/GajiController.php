<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterPegawai;
use App\Absensi;
use App\MasterGaji;
use App\MasterHariKerja;
use Carbon\Carbon;

class GajiController extends Controller
{
    public function index()
    {
        $pegawai = MasterPegawai::all();
        return view('gaji.index', compact('pegawai'));
    }

    public function create()
    {
        $pegawai = MasterPegawai::all();
        return view('gaji.create', compact('pegawai'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pegawai_id' => 'required|exists:master_pegawai,id',
            'gaji_pokok' => 'required|numeric',
            'denda_keterlambatan' => 'required|numeric',
            'potongan_hari' => 'required|numeric',
        ]);

        $masterGaji = MasterGaji::updateOrCreate(
            ['pegawai_id' => $request->pegawai_id],
            [
                'gaji_pokok' => $request->gaji_pokok,
                'denda_keterlambatan' => $request->denda_keterlambatan,
                'potongan_hari' => $request->potongan_hari,
            ]
        );

        return redirect()->route('gaji.index')->with('success', 'Data master gaji berhasil disimpan.');
    }

    // public function calculate(Request $request)
    // {
    //     $pegawai_id = $request->input('pegawai_id');
    //     $pegawai = MasterPegawai::findOrFail($pegawai_id);
    //     $absensi = Absensi::where('pegawai_id', $pegawai_id)
    //                       ->whereMonth('tanggal', date('m'))
    //                       ->whereYear('tanggal', date('Y'))
    //                       ->get();

    //     $masterGaji = MasterGaji::where('pegawai_id', $pegawai_id)->first();

    //     if (!$masterGaji) {
    //         return redirect()->back()->withErrors('Data gaji untuk pegawai ini tidak ditemukan.');
    //     }

    //     $dendaKeterlambatan = 0;
    //     $potonganKetidakhadiran = 0;

    //     foreach ($absensi as $absen) {
    //         if ($absen->jam_masuk && $absen->jam_masuk > $this->getJamMasuk()) {
    //             $dendaKeterlambatan += $masterGaji->denda_keterlambatan;
    //         }

    //         if (!$absen->jam_masuk || !$absen->jam_pulang) {
    //             $potonganKetidakhadiran += $masterGaji->potongan_hari;
    //         }
    //     }

    //     $gajiBersih = $pegawai->gaji_pokok - $dendaKeterlambatan - $potonganKetidakhadiran;

    //     return view('gaji.result', compact('pegawai', 'masterGaji', 'dendaKeterlambatan', 'potonganKetidakhadiran', 'gajiBersih'));
    // }

    // private function getJamMasuk()
    // {
    //     // Get jam_masuk dari master_hari_kerja atau waktu default
    //     return '09:00:00';
    // }

    // v2
    // public function calculate(Request $request)
    // {
    //     $pegawai_id = $request->input('pegawai_id');
    //     $pegawai = MasterPegawai::findOrFail($pegawai_id);
    //     $absensi = Absensi::where('pegawai_id', $pegawai_id)
    //                       ->whereMonth('tanggal', date('m'))
    //                       ->whereYear('tanggal', date('Y'))
    //                       ->get();

    //     $masterGaji = MasterGaji::where('pegawai_id', $pegawai_id)->first();

    //     if (!$masterGaji) {
    //         return redirect()->back()->withErrors('Data gaji untuk pegawai ini tidak ditemukan.');
    //     }

    //     $dendaKeterlambatan = 0;
    //     $potonganKetidakhadiran = 0;

    //     foreach ($absensi as $absen) {
    //         // Menghitung denda keterlambatan
    //         if ($absen->jam_masuk && $absen->jam_masuk > $this->getJamMasuk()) {
    //             $dendaKeterlambatan += $masterGaji->denda_keterlambatan;
    //         }

    //         // Menghitung potongan ketidakhadiran
    //         if (!$absen->jam_masuk || !$absen->jam_pulang) {
    //             $potonganKetidakhadiran += $masterGaji->potongan_hari;
    //         }
    //     }

    //     $gajiBersih = $pegawai->gaji_pokok - $dendaKeterlambatan - $potonganKetidakhadiran;

    //     return view('gaji.result', compact('pegawai', 'masterGaji', 'dendaKeterlambatan', 'potonganKetidakhadiran', 'gajiBersih'));
    // }

    // private function getJamMasuk()
    // {
    //     // Get jam_masuk dari master_hari_kerja atau waktu default
    //     return '09:00:00'; // Sesuaikan dengan waktu yang relevan
    // }

    // v3 - right
    public function calculate(Request $request)
    {
        $pegawai_id = $request->input('pegawai_id');
        $pegawai = MasterPegawai::findOrFail($pegawai_id);
        $absensi = Absensi::where('pegawai_id', $pegawai_id)
                          ->whereMonth('tanggal', date('m'))
                          ->whereYear('tanggal', date('Y'))
                          ->get();

        $masterGaji = MasterGaji::where('pegawai_id', $pegawai_id)->first();

        if (!$masterGaji) {
            return redirect()->back()->withErrors('Data gaji untuk pegawai ini tidak ditemukan.');
        }

        $dendaKeterlambatan = 0;
        $potonganKetidakhadiran = 0;
        $waktuMulaiKerja = Carbon::parse($this->getJamMasuk());

        foreach ($absensi as $absen) {
            $jamMasuk = Carbon::parse($absen->jam_masuk);
            
            // Menghitung denda keterlambatan
            if ($absen->jam_masuk && $jamMasuk->gt($waktuMulaiKerja->addMinutes(15))) {
                $dendaKeterlambatan += $masterGaji->denda_keterlambatan;
            }

            // Menghitung potongan ketidakhadiran
            if (!$absen->jam_masuk || !$absen->jam_pulang) {
                $potonganKetidakhadiran += $masterGaji->potongan_hari;
            }
        }

        $gajiBersih = $pegawai->gaji_pokok - $dendaKeterlambatan - $potonganKetidakhadiran;

        return view('gaji.result', compact('pegawai', 'masterGaji', 'dendaKeterlambatan', 'potonganKetidakhadiran', 'gajiBersih'));
    }

    // v all
    private function getJamMasuk()
    {
        // Ambil waktu mulai kerja dari master_hari_kerja, jika tidak ada ambil default
        $hariKerja = MasterHariKerja::where('hari', Carbon::now()->format('l'))->first();
        return $hariKerja ? $hariKerja->jam_masuk : '08:00:00';
    }

    // // v4
    // public function calculate(Request $request)
    // {
    //     $pegawai_id = $request->input('pegawai_id');
    //     $pegawai = MasterPegawai::findOrFail($pegawai_id);
    //     $absensi = Absensi::where('pegawai_id', $pegawai_id)
    //                       ->whereMonth('tanggal', date('m'))
    //                       ->whereYear('tanggal', date('Y'))
    //                       ->get();

    //     $masterGaji = MasterGaji::where('pegawai_id', $pegawai_id)->first();

    //     if (!$masterGaji) {
    //         return redirect()->back()->withErrors('Data gaji untuk pegawai ini tidak ditemukan.');
    //     }

    //     $dendaKeterlambatan = 0;
    //     $potonganKetidakhadiran = 0;
    //     $waktuMulaiKerja = Carbon::parse($this->getJamMasuk());

    //     foreach ($absensi as $absen) {
    //         $jamMasuk = Carbon::parse($absen->jam_masuk);

    //         // Menghitung denda keterlambatan
    //         if ($absen->jam_masuk && $jamMasuk->gt($waktuMulaiKerja->addMinutes(15))) {
    //             $dendaKeterlambatan += $masterGaji->denda_keterlambatan;
    //         }

    //         // Menghitung potongan ketidakhadiran
    //         if (!$absen->jam_masuk || !$absen->jam_pulang) {
    //             $potonganKetidakhadiran += $masterGaji->potongan_hari;
    //         }
    //     }

    //     $gajiBersih = $pegawai->gaji_pokok - $dendaKeterlambatan - $potonganKetidakhadiran;

    //     return view('gaji.result', compact('pegawai', 'masterGaji', 'dendaKeterlambatan', 'potonganKetidakhadiran', 'gajiBersih'));
    // }

    // v5
    // Method untuk menghitung gaji
    // public function calculate(Request $request)
    // {
    //     $request->validate([
    //         'pegawai_id' => 'required|exists:master_pegawai,id',
    //         'periode' => 'required|date_format:Y-m'
    //     ]);

    //     $pegawai = MasterPegawai::find($request->pegawai_id);
    //     $periode = Carbon::parse($request->periode);

    //     // Menghitung total keterlambatan
    //     $totalDendaKeterlambatan = $this->calculateDendaKeterlambatan($pegawai->id, $periode);
    //     // Menghitung total ketidakhadiran
    //     $totalPotonganKetidakhadiran = $this->calculatePotonganKetidakhadiran($pegawai->id, $periode);

    //     $gajiBersih = $pegawai->gaji_pokok - $totalDendaKeterlambatan - $totalPotonganKetidakhadiran;

    //     $dataGaji = [
    //         'pegawai' => $pegawai,
    //         'masterGaji' => $pegawai,
    //         'totalDendaKeterlambatan' => $totalDendaKeterlambatan,
    //         'totalPotonganKetidakhadiran' => $totalPotonganKetidakhadiran,
    //         'gajiBersih' => $gajiBersih,
    //     ];

    //     return view('gaji.result', compact('pegawai', 'totalDendaKeterlambatan', 'totalPotonganKetidakhadiran', 'gajiBersih'));

    //     // return view('gaji.result', ['gaji' => $dataGaji]);
    // }

    // // Method untuk menghitung total denda keterlambatan
    // protected function calculateDendaKeterlambatan($pegawaiId, $periode)
    // {
    //     // Misal denda keterlambatan Rp.50.000 per 15 menit keterlambatan
    //     $dendaPerMenit = 50000 / 15;
    //     $startOfMonth = $periode->startOfMonth();
    //     $endOfMonth = $periode->endOfMonth();

    //     $absensi = Absensi::where('pegawai_id', $pegawaiId)
    //         ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
    //         ->whereNotNull('jam_masuk')
    //         ->where('jam_masuk', '>', $this->getJamMasukKerja($pegawaiId))
    //         ->get();

    //     $totalDenda = 0;
    //     foreach ($absensi as $absen) {
    //         $jamMasukKerja = Carbon::parse($this->getJamMasukKerja($pegawaiId));
    //         $jamMasuk = Carbon::parse($absen->jam_masuk);
    //         $dendaMenit = $jamMasuk->diffInMinutes($jamMasukKerja);

    //         $totalDenda += $dendaPerMenit * $dendaMenit;
    //     }

    //     return $totalDenda;
    // }

    // // Method untuk menghitung total potongan ketidakhadiran
    // protected function calculatePotonganKetidakhadiran($pegawaiId, $periode)
    // {
    //     // Misal potongan ketidakhadiran Rp.100.000 per hari tidak hadir
    //     $potonganPerHari = 100000;
    //     $startOfMonth = $periode->startOfMonth();
    //     $endOfMonth = $periode->endOfMonth();

    //     $totalHadir = Absensi::where('pegawai_id', $pegawaiId)
    //         ->whereBetween('tanggal', [$startOfMonth, $endOfMonth])
    //         ->count();

    //     $totalHariKerja = $periode->daysInMonth;
    //     $jumlahHariTidakHadir = $totalHariKerja - $totalHadir;

    //     return $jumlahHariTidakHadir * $potonganPerHari;
    // }

    // // Mendapatkan jam masuk kerja dari master hari kerja
    // protected function getJamMasukKerja($pegawaiId)
    // {
    //     $hari = Carbon::now()->format('l'); // Mendapatkan hari dalam bahasa Inggris
    //     $hariKerja = \App\MasterHariKerja::where('hari', $hari)->first();

    //     return $hariKerja ? $hariKerja->jam_masuk : '08:00:00'; // Default jam masuk jika tidak ditemukan
    // }


    // 
}
