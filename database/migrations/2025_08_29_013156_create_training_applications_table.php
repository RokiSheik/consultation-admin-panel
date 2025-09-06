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
        Schema::create('training_applications', function (Blueprint $table) {
             $table->id();
            // Section 1: Personal & Contact Information
            $table->string('full_name');
            $table->string('email_address')->unique();
            $table->string('phone_number');
            $table->string('whatsapp_number');
            $table->string('city_country');
            $table->integer('age');
            
            // Section 2: Business/Career Information
            $table->string('current_occupation')->nullable();
            $table->text('current_work_description')->nullable();
            $table->text('current_stage_revenue_structure')->nullable();
            $table->string('average_monthly_income')->nullable();
            $table->string('invested_in_development')->nullable();
            $table->text('investment_details')->nullable();
            
            // Section 3: Goals & Challenges
            $table->string('biggest_goal')->nullable();
            $table->text('top_challenges')->nullable();
            $table->string('program_applying_for')->nullable();
            $table->text('expected_result')->nullable();
            $table->text('why_good_fit')->nullable();
            
            // Section 4: Commitment & Readiness
            $table->string('commitment_level')->nullable();
            $table->string('ready_to_invest')->nullable();
            $table->string('expected_budget')->nullable();
            
            // Section 5: Final
            $table->string('how_did_you_hear')->nullable();
            $table->text('additional_info')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_applications');
    }
};
