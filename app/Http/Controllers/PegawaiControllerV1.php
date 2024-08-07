<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterPegawai;

class PegawaiControllerV1 extends Controller
{
    public function index()
    {
        $pegawai = MasterPegawai::all();
        return view('pegawai.index', compact('pegawai'));
    }

    public function store(Request $request)
    {
        MasterPegawai::create($request->all());
        return redirect()->back();
    }
}
