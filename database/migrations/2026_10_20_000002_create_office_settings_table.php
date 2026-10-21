<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('office_settings', function (Blueprint $table) {
            $table->id();
            $table->string('clinic_name', 150)->default('Consultorio Dentissa');
            $table->string('clinic_address', 500)->nullable();
            $table->string('map_url', 1000)->nullable();
            $table->string('whatsapp_phone', 30)->nullable();
            $table->timestamps();
        });

        DB::table('office_settings')->insert([
            'id' => 1,
            'clinic_name' => 'Consultorio Dentissa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('office_settings');
    }
};