@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Absensi</h1>
    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pegawai_id">Pegawai</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control">
                @foreach($pegawai as $p)
                    <option value="{{ $p->id }}">{{ $p->nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="tanggal">Tanggal</label>
            <input type="date" name="tanggal" id="tanggal" class="form-control">
        </div>
        <div class="form-group">
            <label for="jam_masuk">Jam Masuk</label>
            <input type="time" name="jam_masuk" id="jam_masuk" class="form-control">
        </div>
        <div class="form-group">
            <label for="jam_keluar">Jam Keluar</label>
            <input type="time" name="jam_keluar" id="jam_keluar" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection
