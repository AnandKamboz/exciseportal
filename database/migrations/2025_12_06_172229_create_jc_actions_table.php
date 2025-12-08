<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(){
    Schema::create('jc_actions', function (Blueprint $table) {
        $table->id();
        // COMPLAINT info
        $table->unsignedBigInteger('complaint_id');
        $table->string('application_id');

        // JC DETAILS
        $table->unsignedBigInteger('jc_id');  // auth()->id()
        $table->string('jc_name')->nullable();
        $table->string('jc_mobile')->nullable();
        $table->string('jc_district')->nullable();

        // ACTION DETAILS
        $table->string('action_type'); 
        $table->unsignedBigInteger('assigned_detc_id')->nullable();
        $table->string('assigned_detc_name')->nullable();
        $table->text('remarks')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jc_actions');
    }
};
