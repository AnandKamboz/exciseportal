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
        Schema::table('users', function (Blueprint $table) {
             $table->boolean('is_active')
                  ->default(1)
                  ->after('ward_no')
                  ->comment('1 = Active, 0 = Deactive');

            // Soft delete flag
            $table->boolean('is_deleted')
                  ->default(0)
                  ->after('is_active')
                  ->comment('0 = Not Deleted, 1 = Soft Deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_active', 'is_deleted']);
        });
    }
};
