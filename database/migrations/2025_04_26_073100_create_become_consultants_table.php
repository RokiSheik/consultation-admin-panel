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
        Schema::create('become_consultants', function (Blueprint $table) {
            $table->id();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('company');
        $table->string('title');
        $table->string('email');
        $table->string('phone');
        $table->string('website_link')->nullable();
        $table->string('video_link')->nullable();
        $table->string('speaking_fees')->nullable();
        $table->text('speaking_experience')->nullable();
        $table->text('topics')->nullable();
        $table->text('why_join')->nullable();
        $table->boolean('receive_updates')->default(false);
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('become_consultants');
    }
};
