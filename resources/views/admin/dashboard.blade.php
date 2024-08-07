@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Dashboard Admin</h1>

    <a href="{{ url('/pegawai') }}" class="btn btn-success">Pegawai</a>
    <a href="{{ url('/gaji') }}" class="btn btn-success">Gaji</a>
    <a href="{{ url('/hari-kerja') }}" class="btn btn-success">Hari Kerja</a>
    <a href="{{ url('/absensi') }}" class="btn btn-success">Absensi</a>
</div>
@endsection
