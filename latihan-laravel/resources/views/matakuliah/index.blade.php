@extends('layouts.app')

@section('judul', 'Daftar Mata Kuliah')

@section('konten')
    <h1 class="h3 mb-4">Daftar Mata Kuliah</h1>
    <x-kartu-info judul="Informasi">
    Data pada halaman ini masih berupa array statis. Pada modul
    berikutnya data akan diambil dari basis data.
    </x-kartu-info>
    <form action="{{ route('matakuliah.index') }}" method="GET" class="mb-4">
        <div class="input-group">
            <input
                type="text"
                name="cari"
                class="form-control"
                placeholder="Cari mata kuliah"
                value="{{ $cari }}">
            <button type="submit" class="btn btn-primary">
                Cari
            </button>
        </div>
        *contoh pemograman dasar
    </form>
    <table class="table table-bordered bg-white">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama</th>
                <th>SKS</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($matakuliah as $mk)
                <tr>
                    <td>{{ $mk['kode'] }}</td>
                    <td>{{ $mk['nama'] }}</td>
                    <td>
                        <x-badge-sks sks="{{ $mk['SKS'] }}" />
                    </td>
                    <td>
                        <a
                            href="{{ route('matakuliah.show', $mk['kode']) }}"
                            class="btn btn-sm btn-primary"
                        >
                            Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Mata kuliah tidak ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection