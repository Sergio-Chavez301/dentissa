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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();                    
            $table->string('nombre');
            $table->string('apellidos');
            $table->string('email')->unique()->nullable();
            $table->string('password');
            
            // Relación corregida con la tabla 'roles' (asegúrate de que la migración de roles se cree antes que esta)
            $table->foreignId('role_id')->constrained('roles')->onDelete('restrict');            
            $table->rememberToken(); // Recomendado para la opción "Recordarme en este equipo" del login
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
