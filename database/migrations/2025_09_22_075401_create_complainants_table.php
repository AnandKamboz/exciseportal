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
        Schema::create('complainants', function (Blueprint $table) {
            $table->id();
            $table->string('secure_id', 64)->unique();
            $table->string('application_id', 50)->unique()->nullable(); 
            $table->string('complainant_name');
            $table->string('complainant_phone', 10);
            $table->string('complainant_email')->nullable();
            $table->string('complainant_aadhar', 12);
            $table->text('complainant_address');
            $table->text('complainant_state');
            $table->string('complainant_city')->nullable();
            $table->string('complainant_district')->nullable();
            $table->string('district')->nullable();
            $table->string('involved_type')->nullable();
            $table->enum('complaint_type', ['gst', 'excise', 'vat']);
                // 🔹 Step 3 — GST-related Fields
            $table->string('gst_firm_name')->nullable();
            $table->string('gst_gstin', 15)->nullable();
            $table->text('gst_firm_address')->nullable();
            $table->string('gst_proof')->nullable();
            $table->string('gst_locality')->nullable();
            $table->string('gst_city')->nullable();
            $table->text('gst_description')->nullable();
            $table->string('gst_vehicle_number', 15)->nullable(); 
            $table->string('gst_person_name')->nullable();    

            // 🔹 Step 3 — Excise-related Fields
            $table->string('excise_name')->nullable();
            $table->string('excise_city')->nullable();
            $table->string('excise_desc')->nullable();
            $table->string('excise_place')->nullable();
            $table->string('excise_time')->nullable();
            $table->text('excise_details')->nullable();
            $table->string('excise_vehicle_number', 15)->nullable();
            $table->string('excise_proof')->nullable();

            // 🔹 Step 3 — VAT Additional Fields
            $table->string('vat_locality')->nullable();
            $table->string('vat_city')->nullable();
            $table->text('vat_description')->nullable();
            $table->string('vat_vehicle_number', 15)->nullable(); 
            $table->string('vat_firm_name')->nullable();
            $table->string('vat_tin')->nullable();
            $table->text('vat_firm_address')->nullable();
            $table->string('vat_person_name')->nullable();  
            $table->string('vat_proof')->nullable();

            // 🔹 System Fields
            $table->unsignedBigInteger('user_id')->nullable()->comment('Linked user ID if logged in');
            $table->string('declaration')->default("0");
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complainants');
    }
};
