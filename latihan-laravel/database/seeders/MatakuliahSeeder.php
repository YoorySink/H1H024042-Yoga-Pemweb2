<?php

namespace Database\Seeders;

use App\Models\Matakuliah;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MatakuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $matakuliah = [
        [
            'kode' => 'IF101',
            'nama' => 'infrastruktur teknologi indonesia',
            'sks' => 2,
        ],
        [
            'kode' => 'IF102',
            'nama' => 'Basis banget',
            'sks' => 3,
        ],
        [
            'kode' => 'IF103',
            'nama' => 'Pemrograman prambanan',
            'sks' => 2,
        ],
        [
            'kode' => 'IF104',
            'nama' => 'Sistem manual',
            'sks' => 3,
        ],
        [
            'kode' => 'IF105',
            'nama' => 'Jaringan sosial',
            'sks' => 1,
        ],
    ];

        foreach ($matakuliah as $data) {
            Matakuliah::updateOrCreate(
                ['kode' => $data['kode']],
                $data
            );
        }
    }
}
