@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Form Gaji Pegawai</h1>
    <form action="{{ route('gaji.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pegawai_id">Pegawai</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control">
                <option value="">Pilih Pegawai</option>
                @foreach($pegawai as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="komponen_gaji">Komponen Gaji</label>
            <input type="text" name="komponen_gaji" id="komponen_gaji" class="form-control">
        </div>
        <div class="form-group">
            <label for="denda_keterlambatan">Denda Keterlambatan</label>
            <input type="number" name="denda_keterlambatan" id="denda_keterlambatan" class="form-control">
        </div>
        <div class="form-group">
            <label for="potongan_harian">Potongan Harian</label>
            <input type="number" name="potongan_harian" id="potongan_harian" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>

    <h2>Daftar Gaji</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Pegawai</th>
                <th>Komponen Gaji</th>
                <th>Denda Keterlambatan</th>
                <th>Potongan Harian</th>
            </tr>
        </thead>
        <tbody>
            @foreach($gaji as $g)
                <tr>
                    <td>{{ $g->pegawai->nama }}</td>
                    <td>{{ $g->komponen_gaji }}</td>
                    <td>{{ $g->denda_keterlambatan }}</td>
                    <td>{{ $g->potongan_harian }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
