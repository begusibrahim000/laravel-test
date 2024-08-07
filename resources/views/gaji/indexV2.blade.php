@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Gaji</h1>
    <form action="{{ route('gaji.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pegawai_id">Pegawai</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control">
                @foreach($gaji as $g)
                    <option value="{{ $g->pegawai_id }}">{{ $g->pegawai->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="gaji_pokok">Gaji Pokok</label>
            <input type="number" name="gaji_pokok" id="gaji_pokok" class="form-control">
        </div>
        <div class="form-group">
            <label for="denda_keterlambatan">Denda Keterlambatan</label>
            <input type="number" name="denda_keterlambatan" id="denda_keterlambatan" class="form-control">
        </div>
        <div class="form-group">
            <label for="potongan_hari">Potongan Hari</label>
            <input type="number" name="potongan_hari" id="potongan_hari" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
