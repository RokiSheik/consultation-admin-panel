<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('podcast_requests', function (Blueprint $table) {
            $table->id();
            $table->string('podcast_name');
            $table->string('full_name');
            $table->string('phone_number');
            $table->string('whatsapp_number');
            $table->string('email');
            $table->string('website_url');
            $table->text('social_media_links')->nullable();
            $table->text('podcast_description')->nullable();
            $table->string('request_speaker')->nullable();
            $table->string('interview_mode')->nullable();
            $table->text('reason_for_guest')->nullable();
            $table->string('interview_length')->nullable();
            $table->text('talking_points')->nullable();
            $table->string('average_views')->nullable();
            $table->boolean('media_presence_agreement')->default(false);
            $table->boolean('share_raw_footage_agreement')->default(false);
            $table->boolean('final_approval_agreement')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('podcast_requests');
    }
};
