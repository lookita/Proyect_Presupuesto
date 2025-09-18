<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('presupuesto_detalles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('presupuesto_id')->constrained()->onDelete('cascade');
            $table->foreignId('producto_id')->constrained()->onDelete('cascade');
            $table->integer('cantidad');
            $table->decimal('precio_unitario', 10, 2);
            $table->decimal('descuento_aplicado', 5, 2)->nullable(); // agregado en Día 8 para lógica de descuentos por producto
            $table->decimal('subtotal', 10, 2); // agregado en Día 8 para cálculo de totales por ítem
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presupuesto_detalles');
    }
};
