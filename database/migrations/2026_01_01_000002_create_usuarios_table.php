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
                    
                    // Relación obligatoria con la tabla Roles
                    $table->foreignId('user_id')->constrained('usuarios')->onDelete('restrict');
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
