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
        Schema::create('affiliate_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('email_address');
            $table->string('phone_number');
            $table->string('whatsapp_number');
            $table->string('location');
            $table->string('website_social_media_link');
            $table->string('primary_audience');
            $table->string('followers_subscribers');
            $table->text('promotion_platforms');
            $table->text('reason_to_join');
            $table->string('done_affiliate_marketing');
            $table->string('how_heard_about_us');
            $table->boolean('agreement')->default(false);

            $table->enum('status', ['pending', 'approved', 'declined'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('affiliate_requests');
    }
};
