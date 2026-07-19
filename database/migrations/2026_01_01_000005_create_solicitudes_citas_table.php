<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->enum('estado', ['esperando_confirmacion', 'cancelada', 'confirmada'])
                ->default('esperando_confirmacion');
            $table->date('fecha_nacimiento');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_citas');
    }
};
