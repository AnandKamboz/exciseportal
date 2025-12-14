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
        Schema::create('missing_info_histories', function (Blueprint $table) {
            $table->id();

            // Complaint / Application
            $table->string('application_id');
            $table->string('complain_secure_id');

            // Applicant (User)
            $table->unsignedBigInteger('applicant_user_id')->nullable();
            
            // DETC details
            $table->unsignedBigInteger('detc_user_id')->nullable();
            $table->unsignedBigInteger('detc_district_id')->nullable();

            // Missing info details
            $table->string('missing_key'); // gst_number / firm_location / address
            $table->text('detc_remarks')->nullable();
            $table->text('submitted_value')->nullable();

            // Timeline
            $table->timestamp('detc_marked_at')->nullable();
            $table->timestamp('applicant_submitted_at')->nullable();

            // Audit
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('missing_info_histories');
    }
};
