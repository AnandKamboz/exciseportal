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
        Schema::create('detc_actions', function (Blueprint $table) {
            $table->id();
            $table->string('secure_id')->unique();
            $table->unsignedBigInteger('complaint_id');
            $table->string('detc_district');
            $table->string('user_application_id');
            $table->enum('proposed_action', ['actionable', 'non_actionable']);
            $table->string('action_taken')->nullable();
            $table->string('reason')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('detc_user_id');
            $table->timestamps();
            // OPTIONAL: foreign key relation
            // $table->foreign('complaint_id')->references('id')->on('complaints')->onDelete('cascade');
        });
              
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detc_actions');
    }
};
