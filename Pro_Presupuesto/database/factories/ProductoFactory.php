<?php

namespace Database\Factories;

use App\Models\Producto;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Producto::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        // Generamos un precio realista y un código único
        $price = $this->faker->randomFloat(2, 5.00, 750.00); 
        
        return [
            'nombre' => $this->faker->words(rand(2, 4), true), // Genera 2-4 palabras como nombre
            'codigo' => $this->faker->unique()->bothify('PROD-####??'), // Ej: PROD-1234AB
            'precio' => $price,
        ];
    }
}