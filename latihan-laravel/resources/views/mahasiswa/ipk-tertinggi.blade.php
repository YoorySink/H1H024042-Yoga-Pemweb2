@extends('layouts.app')

@section('judul', '10 Mahasiswa dengan IPK Tertinggi')

@section('konten')
    <h1 class="h3 mb-4">10 Mahasiswa dengan IPK Tertinggi</h1>

    <table class="table table-striped table-bordered bg-white">
        <thead>
            <tr>
                <th>No.</th>
                <th>NIM</th>
                <th>Nama</th>
                <th>Program Studi</th>
                <th>IPK</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mahasiswa as $siswa)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $siswa->nim }}</td>
                    <td>{{ $siswa->nama }}</td>
                    <td>{{ $siswa->programStudi->nama }}</td>
                    <td>{{ $siswa->ipk }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Belum ada mahasiswa dari program studi Teknik Komputer.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
