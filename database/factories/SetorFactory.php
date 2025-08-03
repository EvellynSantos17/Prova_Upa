<?php

namespace Database\Factories;

use App\Models\Setor;
use Illuminate\Database\Eloquent\Factories\Factory;

class SetorFactory extends Factory
{
    protected $model = Setor::class;

    public function definition()
    {
        return [
            'nome' => $this->faker->word(),
        ];
    }
}
