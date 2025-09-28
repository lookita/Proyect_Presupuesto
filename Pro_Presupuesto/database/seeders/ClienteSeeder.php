<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cliente;
use App\Models\User;

class ClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::first(); 
        
        if (!$adminUser) {
            echo "Error: L'usuari administrador no existeix. Executa UserSeeder primer.\n";
            return;
        }
        
        // Crear 10 clients de prova, vinculant-los a l'usuari administrador.
        Cliente::factory()
            ->count(10)
            ->create([
                // Sobreescriu el valor de user_id del Factory
                'user_id' => $adminUser->id, 
            ]);
    }
}