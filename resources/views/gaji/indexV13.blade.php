@extends('layouts.app')

@section('content')
<div class="container">
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif

    <h1>Buat data Gaji Pegawai</h1>
    <a href="{{ route('gaji.create') }}" class="btn btn-primary">Create</a>

    <h1>Hitung Gaji Pegawai</h1>
    <form action="{{ route('gaji.calculate') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pegawai_id">Pilih Pegawai</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control" required>
                @foreach ($pegawai as $peg)
                    <option value="{{ $peg->id }}">{{ $peg->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Hitung Gaji</button>
    </form>
</div>
@endsection
