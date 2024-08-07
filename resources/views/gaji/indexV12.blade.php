@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Penghitungan Gaji</h1>
    <form action="{{ route('gaji.calculate') }}" method="POST">
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
            <label for="periode">Periode (YYYY-MM)</label>
            <input type="month" name="periode" id="periode" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Hitung Gaji</button>
    </form>
</div>
@endsection
