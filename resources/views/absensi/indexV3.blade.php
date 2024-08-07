@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Absensi</h1>
    <form action="{{ route('absensi.store') }}" method="POST">
        @csrf
        <label for="pegawai_id">Pegawai:</label>
        <select name="pegawai_id" id="pegawai_id">
            @foreach(App\MasterPegawai::all() as $pegawai)
                <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
            @endforeach
        </select>
        <br>
        <label for="tanggal">Tanggal:</label>
        <input type="date" name="tanggal" id="tanggal">
        <br>
        <label for="jam_masuk">Jam Masuk:</label>
        <input type="time" name="jam_masuk" id="jam_masuk">
        <br>
        <label for="jam_pulang">Jam Pulang:</label>
        <input type="time" name="jam_pulang" id="jam_pulang">
        <br>
        <button type="submit">Submit</button>
    </form>

    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif
</div>
@endsection
