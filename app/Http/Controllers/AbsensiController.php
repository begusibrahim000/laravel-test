<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Absensi;
use App\MasterPegawai;
use App\MasterHariKerja;

class AbsensiController extends Controller
{
    public function index()
    {
        $pegawai = MasterPegawai::all();
        return view('absensi.index', compact('pegawai'));
    }

    // public function store(Request $request)
    // {
    //     $absensi = Absensi::create($request->all());
    //     return redirect()->back();
    // }

    // v2
    public function store(Request $request)
    {
        // $request->validate([
        //     'pegawai_id' => 'required|exists:master_pegawai,id',
        //     'tanggal' => 'required|date',
        //     'jam_masuk' => 'nullable|date_format:H:i',
        //     'jam_pulang' => 'nullable|date_format:H:i',
        // ]);

        // dd($request);

        $absensi = Absensi::create($request->all());

        // Implement logic for late entry and absence deduction
        // $this->applyDeductions($absensi);

        return redirect()->route('absensi.index')->with('success', 'Absensi berhasil ditambahkan.');
    }

    // private function applyDeductions($absensi)
    // {
    //     $pegawai = MasterPegawai::find($absensi->pegawai_id);
    //     $hariKerja = MasterHariKerja::first(); // Assuming one set of working hours for simplicity

    //     if (!$absensi->jam_masuk || !$absensi->jam_pulang) {
    //         // Missing attendance check
    //         $potonganHarian = $pegawai->potongan_harian; // Assuming this column exists in Pegawai model
    //         $pegawai->gaji_pokok -= $potonganHarian / 2;
    //     } else {
    //         // Late entry check
    //         $jamMasuk = Carbon::parse($hariKerja->jam_masuk);
    //         $jamMasukAbsensi = Carbon::parse($absensi->jam_masuk);

    //         if ($jamMasukAbsensi->gt($jamMasuk->addMinutes(15))) {
    //             $dendaKeterlambatan = $pegawai->denda_keterlambatan; // Assuming this column exists in Pegawai model
    //             $pegawai->gaji_pokok -= $dendaKeterlambatan;
    //         }
    //     }

    //     // Calculate and deduct absence if needed
    //     if (!$absensi->jam_masuk || !$absensi->jam_pulang) {
    //         $potonganHarian = $pegawai->potongan_harian; // Assuming this column exists in Pegawai model
    //         $pegawai->gaji_pokok -= $potonganHarian;
    //     }

    //     $pegawai->save();
    // }
}
