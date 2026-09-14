<?php

namespace App\Http\Controllers;

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
        $matakuliah = $this->matakuliah;

        if ($cari) {
            $matakuliah = collect($matakuliah)
                ->filter(function ($mk) use ($cari) {
                    return str_contains(
                        strtolower($mk['nama']),
                        strtolower($cari)
                    );
                })
                ->values()
                ->all();
        }

        return view('matakuliah.index', [
            'matakuliah' => $matakuliah,
            'cari' => $cari,
        ]);
    }

    public function show($kode)
    {
        $matakuliah = collect($this->matakuliah)
            ->firstWhere('kode', $kode);

        return view('matakuliah.show', [
            'matakuliah' => $matakuliah,
        ]);
    }
}