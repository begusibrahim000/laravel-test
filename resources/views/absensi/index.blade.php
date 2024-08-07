@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Absensi</h1>
        
        <!-- Display success message -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Absensi Form -->
        <form action="{{ route('absensi.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="pegawai_id">Pegawai:</label>
                <select name="pegawai_id" id="pegawai_id" class="form-control">
                    @foreach(App\MasterPegawai::all() as $pegawai)
                        <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal:</label>
                <input type="date" name="tanggal" id="tanggal" class="form-control">
            </div>

            <div class="form-group">
                <label for="jam_masuk">Jam Masuk:</label>
                <input type="time" name="jam_masuk" id="jam_masuk" class="form-control">
            </div>

            <div class="form-group">
                <label for="jam_pulang">Jam Pulang:</label>
                <input type="time" name="jam_pulang" id="jam_pulang" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
</div>
@endsection
