<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterPegawai;
use App\Absensi;
use Carbon\Carbon;

class GajiControllerV5 extends Controller
{
    public function index(Request $request)
    {
        $pegawaiId = $request->input('pegawai_id');
        $pegawai = MasterPegawai::all();
        $gaji = [];

        if ($pegawaiId) {
            $pegawaiData = MasterPegawai::find($pegawaiId);
            $absensi = Absensi::where('pegawai_id', $pegawaiId)
                ->whereMonth('tanggal', Carbon::now()->month)
                ->get();

            $totalDendaKeterlambatan = 0;
            $totalPotonganKetidakhadiran = 0;
            $totalHariKeterlambatan = 0;
            $totalHariTidakHadir = 0;

            foreach ($absensi as $record) {
                $jamMasukKerja = Carbon::parse('08:00:00');
                $jamKeluarKerja = Carbon::parse('17:00:00');
                $jamMasuk = $record->jam_masuk ? Carbon::parse($record->jam_masuk) : null;
                $jamPulang = $record->jam_pulang ? Carbon::parse($record->jam_pulang) : null;

                if (!$jamMasuk || !$jamPulang) {
                    // Potongan karena tidak hadir
                    $totalHariTidakHadir++;
                } else {
                    // Denda keterlambatan
                    if ($jamMasuk->gt($jamMasukKerja->copy()->addMinutes(15))) {
                        $totalHariKeterlambatan++;
                    }
                    // Potongan karena salah waktu masuk/pulang
                    if ($jamMasuk->gt($jamMasukKerja) || $jamPulang->lt($jamKeluarKerja)) {
                        $totalPotonganKetidakhadiran += ($pegawaiData->gaji_pokok / 2);
                    }
                }
            }

            $totalDendaKeterlambatan = $totalHariKeterlambatan * $pegawaiData->denda_keterlambatan;
            $totalPotonganKetidakhadiran += $totalHariTidakHadir * $pegawaiData->potongan_harian;

            $gajiBersih = $pegawaiData->gaji_pokok - $totalDendaKeterlambatan - $totalPotonganKetidakhadiran;

            $gaji = [
                'pegawai' => $pegawaiData,
                'totalDendaKeterlambatan' => $totalDendaKeterlambatan,
                'totalPotonganKetidakhadiran' => $totalPotonganKetidakhadiran,
                'gajiBersih' => $gajiBersih
            ];
        }

        return view('gaji.index', compact('pegawai', 'gaji'));
    }

    public function generate(Request $request)
    {
        $pegawaiId = $request->input('pegawai_id');
        $pegawai = MasterPegawai::find($pegawaiId);

        if (!$pegawai) {
            return redirect()->route('gaji.index')->with('error', 'Pegawai tidak ditemukan.');
        }

        $absensi = Absensi::where('pegawai_id', $pegawaiId)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->get();

        $totalDendaKeterlambatan = 0;
        $totalPotonganKetidakhadiran = 0;

        foreach ($absensi as $record) {
            $jamMasukKerja = Carbon::parse('08:00:00');
            $jamKeluarKerja = Carbon::parse('17:00:00');
            $jamMasuk = Carbon::parse($record->jam_masuk);
            $jamPulang = Carbon::parse($record->jam_pulang);

            if (!$record->jam_masuk || !$record->jam_pulang) {
                // Potongan karena tidak hadir
                $totalPotonganKetidakhadiran += $pegawai->potongan_harian;
            } else {
                // Denda keterlambatan
                if ($jamMasuk->gt($jamMasukKerja->addMinutes(15))) {
                    $totalDendaKeterlambatan += $pegawai->denda_keterlambatan;
                }
                // Potongan karena salah waktu masuk/pulang
                if ($jamMasuk->gt($jamMasukKerja) || $jamPulang->lt($jamKeluarKerja)) {
                    $totalPotonganKetidakhadiran += ($pegawai->gaji_pokok / 2);
                }
            }
        }

        $gajiBersih = $pegawai->gaji_pokok - $totalDendaKeterlambatan - $totalPotonganKetidakhadiran;

        return view('gaji.index', [
            'pegawai' => $pegawai,
            'gajiBersih' => $gajiBersih,
            'totalDendaKeterlambatan' => $totalDendaKeterlambatan,
            'totalPotonganKetidakhadiran' => $totalPotonganKetidakhadiran,
        ]);
    }
    
}
