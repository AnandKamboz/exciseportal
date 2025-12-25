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
        Schema::table('detc_actions', function (Blueprint $table) {
            $table->unsignedBigInteger('additional_charge_district')
                ->nullable()
                ->after('detc_district');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detc_actions', function (Blueprint $table) {
            $table->dropColumn('additional_charge_district');
        });
    }
};
