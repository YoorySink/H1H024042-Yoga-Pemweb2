<?php

use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MatakuliahController;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Route;

Route::get('/status', function () {
    return response()->json([
        'sukses' => true,
        'pesan' => 'API Pemweb II aktif',
        'waktu' => now()->toIso8601String(),
    ]);
});

Route::apiResource('mahasiswa', MahasiswaController::class);
Route::apiResource('matakuliah', MatakuliahController::class);

Route::get('/program-studi/{programStudi}/mahasiswa', function (ProgramStudi $programStudi) {
    $perHalaman = request()->integer('per_halaman', 10);
    $mahasiswa = $programStudi->mahasiswa()
        ->with('programStudi')
        ->orderBy('nama')
        ->paginate(min($perHalaman, 100));

    return response()->json([
        'sukses' => true,
        'program_studi' => [
            'id' => $programStudi->id,
            'kode' => $programStudi->kode,
            'nama' => $programStudi->nama,
            'jenjang' => $programStudi->jenjang,
        ],
        'data' => \App\Http\Resources\MahasiswaResource::collection($mahasiswa),
        'meta' => [
            'current_page' => $mahasiswa->currentPage(),
            'per_page' => $mahasiswa->perPage(),
            'total' => $mahasiswa->total(),
            'last_page' => $mahasiswa->lastPage(),
        ],
    ]);
})->name('program-studi.mahasiswa');