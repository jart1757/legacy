<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->decimal('total',10,2);
            $table->decimal('pago',10,2)->nullable();
            $table->decimal('descuento',10,2)->nullable();
            $table->decimal('extra',10,2)->nullable();
            $table->date('fecha')->nullable();
            $table->date('fechaing')->nullable();
            $table->string('tipo')->nullable();
            $table->string('departamento')->nullable();
            $table->string('provincia')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('delivery_id')->nullable()->constrained();
            $table->string('pedido_path')->nullable(); // Imagen del pedido
            $table->string('boleta_path')->nullable(); // Imagen de la boleta
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
