@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hasil Penghitungan Gaji</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Nama Pegawai</th>
                <th>Gaji Pokok</th>
                <th>Total Denda Keterlambatan</th>
                <th>Total Potongan Ketidakhadiran</th>
                <th>Gaji Bersih</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $pegawai->nama }}</td>
                <td>{{ number_format($masterGaji->gaji_pokok, 2) }}</td>
                <td>{{ number_format($dendaKeterlambatan, 2) }}</td>
                <td>{{ number_format($potonganKetidakhadiran, 2) }}</td>
                <td>{{ number_format($gajiBersih, 2) }}</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
