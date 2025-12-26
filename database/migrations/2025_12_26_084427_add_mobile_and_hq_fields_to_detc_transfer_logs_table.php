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
        Schema::table('detc_transfer_logs', function (Blueprint $table) {
             $table->string('hq_name')->nullable()->after('transferred_by');
            $table->string('hq_mobile', 10)->nullable()->after('hq_name');

            // From DETC mobile
            $table->string('from_detc_mobile', 10)->nullable()->after('from_detc_name');

            // To DETC mobile
            $table->string('to_detc_mobile', 10)->nullable()->after('to_detc_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detc_transfer_logs', function (Blueprint $table) {
            $table->dropColumn([
                'hq_name',
                'hq_mobile',
                'from_detc_mobile',
                'to_detc_mobile'
            ]);
        });
    }
};
