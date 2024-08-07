<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterPegawai;
use App\MasterGaji;
use App\Absensi;
use Carbon\Carbon;

class GajiControllerV7 extends Controller
{
    public function index(Request $request)
    {
        $pegawai = MasterPegawai::all();
        $gaji = [];

        return view('gaji.index', compact('pegawai', 'gaji'));
    }
    
    // public function generate(Request $request)
    // {
    //     $pegawaiId = $request->input('pegawai_id');
    //     $pegawai = MasterPegawai::find($pegawaiId);
    //     $masterGaji = MasterGaji::where('pegawai_id', $pegawaiId)->first();

    //     if (!$pegawai || !$masterGaji) {
    //         return redirect()->route('gaji.index')->with('error', 'Pegawai tidak ditemukan.');
    //     }

    //     $absensi = Absensi::where('pegawai_id', $pegawaiId)
    //         ->whereMonth('tanggal', Carbon::now()->month)
    //         ->get();

    //     $totalDendaKeterlambatan = 0;
    //     $totalPotonganKetidakhadiran = 0;
    //     $totalKehadiran = 0;

    //     $jamMasukKerja = Carbon::parse('08:00:00');
    //     $jamKeluarKerja = Carbon::parse('17:00:00');

    //     foreach ($absensi as $record) {
    //         $jamMasuk = $record->jam_masuk ? Carbon::parse($record->jam_masuk) : null;
    //         $jamPulang = $record->jam_pulang ? Carbon::parse($record->jam_pulang) : null;

    //         // Check if employee is absent
    //         if (!$jamMasuk || !$jamPulang) {
    //             $totalPotonganKetidakhadiran += $masterGaji->potongan_hari;
    //         } else {
    //             // Check for late arrival
    //             if ($jamMasuk->gt($jamMasukKerja->copy()->addMinutes(15))) {
    //                 $lateMinutes = $jamMasuk->diffInMinutes($jamMasukKerja->copy()->addMinutes(15));
    //                 $totalDendaKeterlambatan += $lateMinutes * $masterGaji->denda_keterlambatan;
    //             }

    //             // Check for improper time in or out
    //             if ($jamMasuk->gt($jamMasukKerja) || $jamPulang->lt($jamKeluarKerja)) {
    //                 $totalPotonganKetidakhadiran += ($masterGaji->gaji_pokok / 2);
    //             }

    //             $totalKehadiran++;
    //         }
    //     }

    //     $totalHadir = $totalKehadiran;
    //     $gajiBersih = $pegawai->gaji_pokok - ($totalDendaKeterlambatan + $totalPotonganKetidakhadiran);

    //     return view('gaji.index', [
    //         'pegawai' => $pegawai,
    //         'totalDendaKeterlambatan' => $totalDendaKeterlambatan,
    //         'totalPotonganKetidakhadiran' => $totalPotonganKetidakhadiran,
    //         'gajiBersih' => $gajiBersih,
    //         'totalHadir' => $totalHadir,
    //     ]);
    // }

    public function generate(Request $request)
{
    $pegawaiId = $request->input('pegawai_id');
    $pegawai = MasterPegawai::find($pegawaiId);
    $masterGaji = MasterGaji::where('pegawai_id', $pegawaiId)->first();

    // Debugging
    // dd($pegawai, $masterGaji);

    if (!$pegawai || !$masterGaji) {
        return redirect()->route('gaji.index')->with('error', 'Pegawai tidak ditemukan.');
    }

    $absensi = Absensi::where('pegawai_id', $pegawaiId)
        ->whereMonth('tanggal', Carbon::now()->month)
        ->get();

    $totalDendaKeterlambatan = 0;
    $totalPotonganKetidakhadiran = 0;
    $totalKehadiran = 0;

    $jamMasukKerja = Carbon::parse('08:00:00');
    $jamKeluarKerja = Carbon::parse('17:00:00');

    foreach ($absensi as $record) {
        $jamMasuk = $record->jam_masuk ? Carbon::parse($record->jam_masuk) : null;
        $jamPulang = $record->jam_pulang ? Carbon::parse($record->jam_pulang) : null;

        // Check if employee is absent
        if (!$jamMasuk || !$jamPulang) {
            $totalPotonganKetidakhadiran += $masterGaji->potongan_hari;
        } else {
            // Check for late arrival
            if ($jamMasuk->gt($jamMasukKerja->copy()->addMinutes(15))) {
                $lateMinutes = $jamMasuk->diffInMinutes($jamMasukKerja->copy()->addMinutes(15));
                $totalDendaKeterlambatan += $lateMinutes * $masterGaji->denda_keterlambatan;
            }

            // Check for improper time in or out
            if ($jamMasuk->gt($jamMasukKerja) || $jamPulang->lt($jamKeluarKerja)) {
                $totalPotonganKetidakhadiran += ($masterGaji->gaji_pokok / 2);
            }

            $totalKehadiran++;
        }
    }

    $totalHadir = $totalKehadiran;
    $gajiBersih = $pegawai->gaji_pokok - ($totalDendaKeterlambatan + $totalPotonganKetidakhadiran);

    return view('gaji.index', [
        'pegawai' => $pegawai,
        'totalDendaKeterlambatan' => $totalDendaKeterlambatan,
        'totalPotonganKetidakhadiran' => $totalPotonganKetidakhadiran,
        'gajiBersih' => $gajiBersih,
        'totalHadir' => $totalHadir,
    ]);
}

    //  public function generate(Request $request)
    // {
    //     $pegawaiId = $request->input('pegawai_id');
    //     $pegawai = MasterPegawai::find($pegawaiId);

    //     if (!$pegawai) {
    //         return redirect()->route('gaji.index')->with('error', 'Pegawai tidak ditemukan.');
    //     }

    //     $absensi = Absensi::where('pegawai_id', $pegawaiId)
    //         ->whereMonth('tanggal', Carbon::now()->month)
    //         ->get();

    //     $totalDendaKeterlambatan = 0;
    //     $totalPotonganKetidakhadiran = 0;

    //     foreach ($absensi as $record) {
    //         $jamMasukKerja = Carbon::parse('08:00:00');
    //         $jamKeluarKerja = Carbon::parse('17:00:00');
    //         $jamMasuk = Carbon::parse($record->jam_masuk);
    //         $jamPulang = Carbon::parse($record->jam_pulang);

    //         if (!$record->jam_masuk || !$record->jam_pulang) {
    //             // Potongan karena tidak hadir
    //             $totalPotonganKetidakhadiran += $pegawai->potongan_harian;
    //         } else {
    //             // Denda keterlambatan
    //             if ($jamMasuk->gt($jamMasukKerja->addMinutes(15))) {
    //                 $totalDendaKeterlambatan += $pegawai->denda_keterlambatan;
    //             }
    //             // Potongan karena salah waktu masuk/pulang
    //             if ($jamMasuk->gt($jamMasukKerja) || $jamPulang->lt($jamKeluarKerja)) {
    //                 $totalPotonganKetidakhadiran += ($pegawai->gaji_pokok / 2);
    //             }
    //         }
    //     }

    //     $gajiBersih = $pegawai->gaji_pokok - $totalDendaKeterlambatan - $totalPotonganKetidakhadiran;

    //     return view('gaji.index', [
    //         'pegawai' => $pegawai,
    //         'gajiBersih' => $gajiBersih,
    //         'totalDendaKeterlambatan' => $totalDendaKeterlambatan,
    //         'totalPotonganKetidakhadiran' => $totalPotonganKetidakhadiran,
    //     ]);
    // }

// 
}
