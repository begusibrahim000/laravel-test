@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <h1>Buat Data Gaji Pegawai</h1>
    <a href="{{ route('gaji.create') }}" class="btn btn-primary mb-3">Create</a>

    <h1>Hitung Gaji Pegawai</h1>
    <form action="{{ route('gaji.calculate') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pegawai_id">Pilih Pegawai</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control" required>
                <!-- @foreach ($pegawai as $peg)
                    <option value="{{ $peg->id }}">{{ $peg->nama }}</option>
                @endforeach -->

                @foreach(App\MasterPegawai::all() as $pegawai)
                    <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                @endforeach
            </select>
        </div>

         <div class="form-group">
            <label for="periode">Periode (YYYY-MM)</label>
            <input type="month" name="periode" id="periode" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Hitung Gaji</button>
    </form>
</div>
@endsection
