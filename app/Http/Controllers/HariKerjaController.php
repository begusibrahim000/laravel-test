<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MasterHariKerja;

class HariKerjaController extends Controller
{
    public function index()
    {
        $hari_kerja = MasterHariKerja::all();
        return view('hari_kerja.index', compact('hari_kerja'));
    }

    // public function store(Request $request)
    // {
    //     MasterHariKerja::create($request->all());
    //     return redirect()->back();
    // }

    public function create()
    {
        return view('hari-kerja.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|string|max:255',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_keluar' => 'required|date_format:H:i'
        ]);

        MasterHariKerja::create($request->all());
        return redirect()->route('hari-kerja.index');
    }
}
