@extends('layouts.app')

@section('judul', 'Detail Mahasiswa')

@section('konten')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3">Profil Mahasiswa</h1>
        <a href="{{ route('mahasiswa.data') }}" class="btn btn-secondary">Kembali</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $mahasiswa->nama }}</h5>
            <p class="card-text">
                <strong>NIM:</strong> {{ $mahasiswa->nim }} <br>
                <strong>Program Studi:</strong> {{ $mahasiswa->programStudi->nama ?? '-' }} <br>
                <strong>Angkatan:</strong> {{ $mahasiswa->angkatan }} <br>
                <strong>IPK:</strong> {{ $mahasiswa->ipk }}
            </p>
        </div>
    </div>

    <h3 class="h4 mb-3">Mata Kuliah yang Diambil</h3>
    
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Kode MK</th>
                <th>Nama Matakuliah</th>
                <th>SKS</th>
                <th>Semester</th>
                <th>Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($mahasiswa->matakuliah as $mk)
                <tr>
                    <td>{{ $mk->kode }}</td>
                    <td>{{ $mk->nama }}</td>
                    <td>
                        <x-badge-sks :sks="$mk->sks" />
                    </td>
                    <td>{{ $mk->semester }}</td>
                    <td>
                        <span class="badge bg-primary fs-6">
                            {{ $mk->pivot->nilai ?? 'Belum ada nilai' }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Mahasiswa ini belum mengambil matakuliah apapun.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection