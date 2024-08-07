<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterPegawai;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = MasterPegawai::all();
        return view('pegawai.index', compact('pegawai'));
    }

    // public function store(Request $request)
    // {
    //     MasterPegawai::create($request->all());
    //     return redirect()->back();
    // }

    // v2

    public function create()
    {
        return view('pegawai.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'gaji_pokok' => 'required|numeric'
        ]);

        MasterPegawai::create($request->all());
        return redirect()->route('pegawai.index');
    }

    public function edit(MasterPegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(Request $request, MasterPegawai $pegawai)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'gaji_pokok' => 'required|numeric'
        ]);

        $pegawai->update($request->all());
        return redirect()->route('pegawai.index');
    }

    public function destroy(MasterPegawai $pegawai)
    {
        $pegawai->delete();
        return redirect()->route('pegawai.index');
    }
}
