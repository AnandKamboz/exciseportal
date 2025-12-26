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
        Schema::table('detc_update_logs', function (Blueprint $table) {
            $table->string('hq_name')->nullable()->after('updated_by');
            $table->string('hq_mobile', 10)->nullable()->after('hq_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detc_update_logs', function (Blueprint $table) {
            $table->dropColumn(['hq_name', 'hq_mobile']);
        });
    }
};
