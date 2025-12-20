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
        Schema::table('complainants', function (Blueprint $table) {
            Schema::table('complainants', function (Blueprint $table) {

            $table->enum('current_owner', [
                'APPLICANT', 'ETO', 'HQ', 'CLOSED','DETC',
            ])->nullable()->after('user_id');

            $table->enum('current_level', [
                'APPLICANT', 'ETO', 'HQ', 'CLOSED', 'DETC',
            ])->nullable()->after('current_owner');

            $table->string('current_status', 100)
                  ->nullable()
                  ->after('current_level');

            $table->boolean('is_final')
                  ->default(false)
                  ->after('current_status');
        });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complainants', function (Blueprint $table) {
            $table->dropColumn([
                'current_owner',
                'current_level',
                'current_status',
                'is_final',
            ]);
        });
    }
};
