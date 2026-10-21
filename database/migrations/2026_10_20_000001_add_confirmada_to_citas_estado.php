<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->enum('estado', ['cancelada', 'realizada', 'en_espera', 'confirmada', 'no_presento'])
                ->default('en_espera')
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->enum('estado', ['cancelada', 'realizada', 'en_espera', 'no_presento'])
                ->default('en_espera')
                ->change();
        });
    }
};