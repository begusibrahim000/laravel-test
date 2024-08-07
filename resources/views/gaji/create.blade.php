@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Tambah Data Master Gaji</h1>
    <form action="{{ route('gaji.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pegawai_id">Pilih Pegawai</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control" required>
                @foreach ($pegawai as $peg)
                    <option value="{{ $peg->id }}">{{ $peg->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="gaji_pokok">Gaji Pokok</label>
            <input type="number" name="gaji_pokok" id="gaji_pokok" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="denda_keterlambatan">Denda Keterlambatan</label>
            <input type="number" name="denda_keterlambatan" id="denda_keterlambatan" class="form-control" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="potongan_hari">Potongan Ketidakhadiran</label>
            <input type="number" name="potongan_hari" id="potongan_hari" class="form-control" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
