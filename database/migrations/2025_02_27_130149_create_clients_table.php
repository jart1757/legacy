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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');//nombre
            $table->string('identificacion')->nullable();//codigo
            $table->string('telefono')->nullable();//carnet
            $table->string('email')->nullable();
            $table->string('empresa')->nullable();
            $table->string('nit')->nullable();//ciudad
            $table->foreignId('category_id')->nullable()->constrained();//a que id pertenece
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
