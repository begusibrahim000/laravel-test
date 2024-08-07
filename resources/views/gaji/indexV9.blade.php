@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Hasil Penghitungan Gaji</h1>

   <form action="{{ route('gaji.generate') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="pegawai_id">Pilih Pegawai</label>
        <select name="pegawai_id" id="pegawai_id" class="form-control" required>
            @foreach($pegawai as $peg)
                <option value="{{ $peg->id }}">{{ $peg->nama }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn btn-primary">Generate</button>
</form>

</div>
@endsection
