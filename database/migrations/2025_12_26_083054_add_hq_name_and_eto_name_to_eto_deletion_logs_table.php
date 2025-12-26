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
        Schema::table('eto_deletion_logs', function (Blueprint $table) {
            $table->string('hq_name')->nullable()->after('id');
            $table->string('eto_name')->nullable()->after('hq_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eto_deletion_logs', function (Blueprint $table) {
            $table->dropColumn(['hq_name', 'eto_name']);
        });
    }
};
