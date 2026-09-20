<?php

namespace Database\Seeders;
use App\Models\Mahasiswa;
use App\Models\Matakuliah;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(ProgramStudiSeeder::class);
        $this->call(MatakuliahSeeder::class);
        $mahasiswa = Mahasiswa::factory()->count(30)->create();
        $matakuliah = Matakuliah::all();

        foreach ($mahasiswa as $siswa) {
            $siswa->matakuliah()->sync(
                $matakuliah->random(min(3, $matakuliah->count()))->mapWithKeys(
                    fn (Matakuliah $mk) => [$mk->id => ['nilai' => fake()->randomElement(['A', 'B', 'C'])]]
                )->all()
            );
        }
    }
}
