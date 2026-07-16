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
        Schema::create('solicitudes_citas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('telefono');
            $table->string('email')->nullable();
            $table->dateTime('fecha_hora_propuesta');
            $table->text('motivo_consulta')->nullable();
            
            // Estado para el control administrativo de la web
            $table->string('estado')->default('pendiente'); // 'pendiente', 'confirmada', 'cancelada'

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitudes_citas');
    }
};
