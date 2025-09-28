<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Presupuesto;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\User; // Asegúrate de que esta línea esté presente

class PresupuestoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clientes = Cliente::all();
        $productos = Producto::all();
        $estados = ['pendiente', 'facturado', 'cancelado'];
        $adminUser = User::first();
        
        // 1. INSTANCIAR FAKER
        $faker = \Faker\Factory::create(); 

        if ($clientes->isEmpty() || $productos->isEmpty() || !$adminUser) {
            echo "Error: Asegúrate de que UserSeeder, ClienteSeeder y ProductoSeeder se hayan ejecutado correctamente.\n";
            return;
        }

        // Crear 20 presupuestos de prueba
        // 2. PASAR $faker AL BLOQUE use()
        Presupuesto::factory()->count(20)->make()->each(function (Presupuesto $presupuesto) use ($clientes, $productos, $estados, $adminUser, $faker) {
            
            // Asignar datos (user_id y cliente_id)
            $presupuesto->cliente_id = $clientes->random()->id;
            $presupuesto->estado = $estados[array_rand($estados)];
            $presupuesto->user_id = $adminUser->id; 
            $presupuesto->save();

            $totalPresupuesto = 0;
            
            // Crear entre 2 y 5 detalles (productos) para este presupuesto
            $detallesCount = rand(2, 5);
            $productosUsados = $productos->shuffle()->take($detallesCount);

            foreach ($productosUsados as $producto) {
                $cantidad = rand(1, 4);
                $precioUnitario = $producto->precio; 
                
                $descuento = $faker->boolean(20) ? $faker->randomFloat(2, 5, 20) : 0; 
                
                $precioFinal = $precioUnitario * (1 - $descuento / 100);
                $subtotal = $precioFinal * $cantidad;

                $presupuesto->detalles()->create([
                    'producto_id' => $producto->id,
                    'cantidad' => $cantidad,
                    'precio_unitario' => $precioUnitario,
                    'descuento_aplicado' => $descuento,
                    'subtotal' => $subtotal,
                ]);

                $totalPresupuesto += $subtotal;
            }

            // Actualizar el total del presupuesto
            $presupuesto->update(['total' => $totalPresupuesto]);
        });
    }
}