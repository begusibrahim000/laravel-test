@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Hasil Penghitungan Gaji</h1>
    @if(isset($gaji['pegawai']))
    <table class="table table-bordered">
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
                <td>{{ $gaji['pegawai']->nama }}</td>
                <td>{{ number_format($gaji['pegawai']->gaji_pokok, 2) }}</td>
                <td>{{ number_format($gaji['totalDendaKeterlambatan'], 2) }}</td>
                <td>{{ number_format($gaji['totalPotonganKetidakhadiran'], 2) }}</td>
                <td>{{ number_format($gaji['gajiBersih'], 2) }}</td>
            </tr>
        </tbody>
    </table>
    @else
    <p>Data gaji tidak ditemukan.</p>
    @endif
</div>
@endsection
