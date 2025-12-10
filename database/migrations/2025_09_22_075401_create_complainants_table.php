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
            $table->string('district_id')->nullable();
            $table->string('district_name')->nullable();
            $table->string('complainant_name')->nullable();
            $table->string('complainant_email')->nullable();
            $table->string('complainant_phone', 10);
            $table->enum('complaint_type', ['gst', 'excise', 'vat']);
            $table->text('gst_description')->nullable();
            $table->string('location')->nullable();
            $table->string('pincode', 6)->nullable();
            $table->string('gst_proof')->nullable();
            
            $table->string('gst_firm_name')->nullable();
            $table->string('gst_gstin', 15)->nullable();
            $table->text('gst_firm_address')->nullable();
           
            $table->string('type_of_complaint')->nullable();
            $table->string('missing_gst_number')->nullable();
            $table->string('missing_firm_location')->nullable();
            $table->string('missing_address')->nullable();
            $table->boolean('detc_rise_issue')->default(0);
            $table->string('detc_issue')->nullable();
            $table->timestamp('missing_info_submitted_at')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->comment('Linked user ID if logged in');
            $table->string('declaration')->default("0");
            $table->boolean('is_completed')->default(false);
            $table->timestamps();



            
            // $table->string('complainant_aadhar', 12)->nullable();
            // $table->string('complainant_address1')->nullable();
            // $table->string('complainant_address2')->nullable();
            // $table->text('complainant_address')->nullable();
            // $table->string('complainant_state')->nullable();  
            // $table->string('complainant_district')->nullable();
            
            // 🔹 Step 3 — GST-related Fields
            
           

           
            
            // $table->string('involved_type')->nullable();
            // $table->string('gst_vehicle_number', 15)->nullable(); 
            // $table->string('gst_person_name')->nullable();
            
            // Missing Field 
           

            // 🔹 Step 3 — Excise-related Fields
            // $table->string('excise_name')->nullable();
            // $table->string('excise_city')->nullable();
            // $table->string('excise_desc')->nullable();
            // $table->string('excise_place')->nullable();
            // $table->string('excise_time')->nullable();
            // $table->text('excise_details')->nullable();
            // $table->string('excise_vehicle_number', 15)->nullable();
            // $table->string('excise_proof')->nullable();
            // 🔹 Step 3 — VAT Additional Fields
            // $table->string('vat_locality')->nullable();
            // $table->string('vat_city')->nullable();
            // $table->text('vat_description')->nullable();
            // $table->string('vat_vehicle_number', 15)->nullable(); 
            // $table->string('vat_firm_name')->nullable();
            // $table->string('vat_tin')->nullable();
            // $table->text('vat_firm_address')->nullable();
            // $table->string('vat_person_name')->nullable();  
            // $table->string('vat_proof')->nullable();
            // 🔹 System Fields
           
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
