<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->timestamp('reminder_sent_at')->nullable()->after('estado');
            $table->index(['estado', 'fecha', 'reminder_sent_at'], 'citas_reminder_lookup_index');
        });
    }

    public function down(): void
    {
        Schema::table('citas', function (Blueprint $table) {
            $table->dropIndex('citas_reminder_lookup_index');
            $table->dropColumn('reminder_sent_at');
        });
    }
};
