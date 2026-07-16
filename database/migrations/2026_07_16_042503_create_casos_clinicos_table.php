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
        Schema::create('casos_clinicos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Paciente bajo tratamiento
            $table->string('tratamiento_base'); // Ej: "Ortodoncia Correctiva"
            $table->integer('progreso')->default(0); // Porcentaje de avance (0 a 100)
            $table->enum('estado', ['activo', 'finalizado'])->default('activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('casos_clinicos');
    }
};
