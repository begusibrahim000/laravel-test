<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterGaji;
use App\MasterPegawai;
use App\Absensi;
use Carbon\Carbon;

class GajiControllerV2 extends Controller
{
    // public function index()
    // {
    //     $gaji = MasterGaji::with('pegawai')->get();
    //     return view('gaji.index', compact('gaji'));
    // }

    // public function store(Request $request)
    // {
    //     MasterGaji::create($request->all());
    //     return redirect()->back();
    // }

    // v2
    // public function index()
    // {
    //     $pegawai = MasterPegawai::all();
    //     $gaji = MasterGaji::with('pegawai')->get();
    //     return view('gaji.index', compact('gaji', 'pegawai'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'pegawai_id' => 'required|exists:pegawai,id',
    //         'komponen_gaji' => 'required|string|max:255',
    //         'denda_keterlambatan' => 'required|numeric',
    //         'potongan_harian' => 'required|numeric',
    //     ]);

    //     MasterGaji::create($request->all());
        
    //     return redirect()->route('gaji.index')->with('success', 'Gaji berhasil ditambahkan.');
    // }

    // v3
    // public function create()
    // {
    //     $pegawai = MasterPegawai::all();
    //     return view('gaji.create', compact('pegawai'));
    // }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'pegawai_id' => 'required|exists:master_pegawai,id',
    //         'gaji_pokok' => 'required|numeric',
    //         'denda_keterlambatan' => 'required|numeric',
    //         'potongan_hari' => 'required|numeric'
    //     ]);

    //     MasterGaji::create($request->all());
    //     return redirect()->route('gaji.index');
    // }

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

            foreach ($absensi as $record) {
                if (!$record->jam_masuk || !$record->jam_pulang) {
                    $totalPotonganKetidakhadiran += $pegawaiData->potongan_harian;
                } else {
                    $jamMasuk = Carbon::parse($record->tanggal . ' ' . $record->jam_masuk);
                    $jamMasukKerja = Carbon::parse($record->tanggal . ' ' . $pegawaiData->jam_masuk);

                    if ($jamMasuk->gt($jamMasukKerja->addMinutes(15))) {
                        $totalDendaKeterlambatan += $pegawaiData->denda_keterlambatan;
                    }
                }
            }

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
        $absensi = Absensi::where('pegawai_id', $pegawaiId)
            ->whereMonth('tanggal', Carbon::now()->month)
            ->get();

        $totalDendaKeterlambatan = 0;
        $totalPotonganKetidakhadiran = 0;

        foreach ($absensi as $record) {
            if (!$record->jam_masuk || !$record->jam_pulang) {
                $totalPotonganKetidakhadiran += $pegawai->potongan_harian;
            } else {
                $jamMasuk = Carbon::parse($record->tanggal . ' ' . $record->jam_masuk);
                $jamMasukKerja = Carbon::parse($record->tanggal . ' ' . $pegawai->jam_masuk);

                if ($jamMasuk->gt($jamMasukKerja->addMinutes(15))) {
                    $totalDendaKeterlambatan += $pegawai->denda_keterlambatan;
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
