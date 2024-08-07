@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Hasil Penghitungan Gaji</h1>

    <!-- Gaji Form -->
    <form action="{{ route('gaji.generate') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="pegawai_id">Pegawai:</label>
            <select name="pegawai_id" id="pegawai_id" class="form-control">
                <option value="">Pilih Pegawai</option>
                @foreach(App\MasterPegawai::all() as $pegawai)
                    <option value="{{ $pegawai->id }}">{{ $pegawai->nama }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Generate Payroll</button>
    </form>

    @if(isset($gaji['pegawai']))
    <table class="table table-bordered mt-4">
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
