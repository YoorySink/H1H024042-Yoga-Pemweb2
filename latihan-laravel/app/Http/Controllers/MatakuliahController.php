<?php

namespace App\Http\Controllers;

use App\Models\Matakuliah;
use Illuminate\Http\Request;

class MatakuliahController extends Controller
{
    private $matakuliah = [
        [
            'kode' => 'IF101',
            'nama' => 'infrastruktur teknologi indonesia',
            'SKS' => 2,
        ],
        [
            'kode' => 'IF102',
            'nama' => 'Basis banget',
            'SKS' => 3,
        ],
        [
            'kode' => 'IF103',
            'nama' => 'Pemrograman prambanan',
            'SKS' => 2,
        ],
        [
            'kode' => 'IF104',
            'nama' => 'Sistem manual',
            'SKS' => 3,
        ],
        [
            'kode' => 'IF105',
            'nama' => 'Jaringan sosial',
            'SKS' => 1,
        ],
    ];

    public function index(Request $request)
    {
        $cari = $request->query('cari');
        $matakuliah = Matakuliah::query()
            ->when($cari, function ($query, $cari) {
                $query->where('nama', 'like', "%{$cari}%");
            })
            ->orderBy('kode')
            ->get();

        return view('matakuliah.index', [
            'matakuliah' => $matakuliah,
            'cari' => $cari,
        ]);
    }

    public function show($kode)
    {
        $matakuliah = Matakuliah::where('kode', $kode)->firstOrFail();

        return view('matakuliah.show', [
            'matakuliah' => $matakuliah,
        ]);
    }
}