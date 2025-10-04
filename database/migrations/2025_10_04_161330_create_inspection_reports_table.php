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
        Schema::create('inspection_reports', function (Blueprint $table) {
            $table->id();

            $table->string('secure_id')->unique();
            $table->string('complain_secure_id')->unique();
            $table->biginteger('inspector_id');

            
            $table->string('source_grading');
            $table->string('information_grading');
            $table->string('process_complain');
            $table->text('remarks');

            // Accept section
            $table->string('proposed_action')->nullable();
            $table->string('commodities_reported')->nullable();
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->text('place_to_search')->nullable();
            $table->string('upload_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_reports');
    }
};
