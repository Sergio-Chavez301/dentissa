<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->string('tratamiento_realizado')->nullable()->after('servicio_id');
            $table->foreignId('servicio_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropColumn('tratamiento_realizado');
        });

        Schema::table('citas', function (Blueprint $table) {
            $table->foreignId('servicio_id')->nullable(false)->change();
        });
    }
};
