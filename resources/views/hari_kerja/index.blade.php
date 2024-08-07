@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Master Hari Kerja</h1>
    <form action="{{ route('hari-kerja.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="hari">Hari</label>
            <input type="text" name="hari" id="hari" class="form-control">
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
    <table class="table mt-3">
        <thead>
            <tr>
                <th>Hari</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($hari_kerja as $h)
                <tr>
                    <td>{{ $h->hari }}</td>
                    <td>{{ $h->jam_masuk }}</td>
                    <td>{{ $h->jam_keluar }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
