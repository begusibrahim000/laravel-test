@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Data Pegawai</h1>
    <form action="{{ route('pegawai.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="nama">Nama</label>
            <input type="text" name="nama" id="nama" class="form-control">
        </div>
        <div class="form-group">
            <label for="jabatan">Jabatan</label>
            <input type="text" name="jabatan" id="jabatan" class="form-control">
        </div>
        <div class="form-group">
            <label for="gaji_pokok">Gaji Pokok</label>
            <input type="number" name="gaji_pokok" id="gaji_pokok" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Gaji Pokok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pegawai as $p)
                <tr>
                    <td>{{ $p->nama }}</td>
                    <td>{{ $p->jabatan }}</td>
                    <td>{{ $p->gaji_pokok }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
