<?php

namespace Database\Factories;

use App\Models\Presupuesto;
use Illuminate\Database\Eloquent\Factories\Factory;

class PresupuestoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Presupuesto::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [

            'cliente_id' => \App\Models\Cliente::factory(), 
            
            'fecha' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'estado' => $this->faker->randomElement(['pendiente', 'facturado', 'cancelado']), 
            
        ];
    }
}