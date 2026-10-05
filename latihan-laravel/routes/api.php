<?php

use App\Http\Controllers\Api\MahasiswaController;
use App\Http\Controllers\Api\MatakuliahController;
use App\Models\ProgramStudi;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

Route::get('/status', function () {
    return response()->json([
        'sukses' => true,
        'pesan' => 'API Pemweb II aktif',
        'waktu' => now()->toIso8601String(),
    ]);
});

// Route::apiResource('mahasiswa', MahasiswaController::class);
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

Route::post('/auth/login', [AuthController::class,'login'])->middleware('throttle:5,1');

Route::post('/auth/register', [AuthController::class, 'register']);

// Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/auth/profil', [AuthController::class, 'profil']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/logout-semua', [
        AuthController::class,
        'logoutSemua'
    ]);
    Route::put('/auth/password', [AuthController::class, 'ubahPassword']);
    Route::get('/mahasiswa', [MahasiswaController::class, 'index']);
    Route::get('/mahasiswa/{mahasiswa}', [
        MahasiswaController::class,
        'show'
    ]);
    Route::middleware('ability:mahasiswa:tulis')->group(function () {
        Route::post('/mahasiswa', [MahasiswaController::class, 'store']);
        Route::put('/mahasiswa/{mahasiswa}', [
            MahasiswaController::class,
            'update'
        ]);
        Route::patch('/mahasiswa/{mahasiswa}', [
            MahasiswaController::class,
            'update'
        ]);
    });
    Route::delete('/mahasiswa/{mahasiswa}', [
        MahasiswaController::class,
        'destroy'
    ])->middleware('peran.admin');
});
