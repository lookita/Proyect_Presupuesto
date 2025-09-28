<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Producto::create(['nombre' => 'Laptop Gamer Pro', 'codigo' => 'LG-P001', 'precio' => 1250.00]);
        Producto::create(['nombre' => 'Monitor 4K UltraWide', 'codigo' => 'MN-UW4K', 'precio' => 480.50]);
        Producto::create(['nombre' => 'Teclado Mecánico RGB', 'codigo' => 'TC-MEC', 'precio' => 85.99]);
        Producto::create(['nombre' => 'Mouse Ergonómico Inalámbrico', 'codigo' => 'MO-ERG', 'precio' => 45.00]);
        Producto::create(['nombre' => 'Servicio de Instalación', 'codigo' => 'SERV-INST', 'precio' => 150.00]);
        
        Producto::factory()->count(15)->create();
    }
}