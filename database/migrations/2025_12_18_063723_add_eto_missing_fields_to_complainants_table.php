<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complainants', function (Blueprint $table) {

            $table->string('eto_missing_gst_number')->nullable()
                ->after('missing_info_submitted_at');

            $table->string('eto_missing_firm_location')->nullable()
                ->after('eto_missing_gst_number');

            $table->string('eto_missing_address')->nullable()
                ->after('eto_missing_firm_location');

            $table->boolean('eto_rise_issue')->default(0)
                ->after('eto_missing_address');

            $table->string('eto_issue')->nullable()
                ->after('eto_rise_issue');

            $table->timestamp('eto_missing_info_submitted_at')->nullable()
                ->after('eto_issue');
        });
    }

    public function down(): void
    {
        Schema::table('complainants', function (Blueprint $table) {
            $table->dropColumn([
                'eto_missing_gst_number',
                'eto_missing_firm_location',
                'eto_missing_address',
                'eto_rise_issue',
                'eto_issue',
                'eto_missing_info_submitted_at',
            ]);
        });
    }
};
