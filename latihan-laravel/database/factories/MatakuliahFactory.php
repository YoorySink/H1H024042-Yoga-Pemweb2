<?php

namespace Database\Factories;

use App\Models\Matakuliah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Matakuliah>
 */
class MatakuliahFactory extends Factory
{
    protected $model = Matakuliah::class;

    public function definition(): array
    {
        return [
            'kode' => 'MK' . fake()->unique()->numerify('###'),
            'nama' => fake('id_ID')->words(3, true),
            'sks' => fake()->numberBetween(1, 6),
            'semester' => fake()->numberBetween(1, 8),
        ];
    }
}
