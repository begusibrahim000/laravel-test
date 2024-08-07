<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterPegawai;
use App\MasterGaji;
use App\Absensi;
use Carbon\Carbon;

class GajiControllerV8 extends Controller
{
    public function index()
    {
        $pegawai = MasterPegawai::all();
        return view('gaji.index', compact('pegawai'));
    }

    public function generate(Request $request)
    {
        $pegawaiId = $request->input('pegawai_id');
        $pegawai = MasterPegawai::find($pegawaiId);
        $masterGaji = MasterGaji::where('pegawai_id', $pegawaiId)->first();

        if (!$pegawai || !$masterGaji) {
            return redirect()->route('gaji.index')->with('error', 'Data pegawai atau gaji tidak ditemukan.');
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
                $totalPotonganKetidakhadiran += $masterGaji->potongan_harian;
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
}
