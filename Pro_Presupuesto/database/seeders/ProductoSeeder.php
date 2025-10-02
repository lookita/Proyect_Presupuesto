<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Productos manuales
        Producto::create(['nombre' => 'Laptop Gamer Pro', 'codigo' => 'LG-P001', 'precio' => 1250.00, 'stock' => 5]);
        Producto::create(['nombre' => 'Monitor 4K UltraWide', 'codigo' => 'MN-UW4K', 'precio' => 480.50, 'stock' => 12]);
        Producto::create(['nombre' => 'Teclado Mecánico RGB', 'codigo' => 'TC-MEC', 'precio' => 85.99, 'stock' => 0]);
        Producto::create(['nombre' => 'Mouse Ergonómico Inalámbrico', 'codigo' => 'MO-ERG', 'precio' => 45.00, 'stock' => 3]);
        Producto::create(['nombre' => 'Servicio de Instalación', 'codigo' => 'SERV-INST', 'precio' => 150.00, 'stock' => 1]);

        // Productos con stock variable para probar estilos
        $stocks = [0, 3, 7, 15, 50];

        foreach ($stocks as $i => $stock) {
            Producto::create([
                'nombre' => 'Producto ' . ($i + 1),
                'codigo' => 'PRD-' . strtoupper(Str::random(6)),
                'precio' => rand(100, 500),
                'stock' => $stock,
            ]);
        }

        // Productos generados por factory
        Producto::factory()->count(15)->create();
    }
}
