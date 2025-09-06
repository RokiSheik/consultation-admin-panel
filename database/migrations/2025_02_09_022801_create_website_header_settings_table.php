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
        Schema::create('website_header_settings', function (Blueprint $table) {
            $table->id();
            $table->string('header_logo')->nullable();
            $table->boolean('enable_sticky_header')->default(false);
            $table->json('header_menu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('website_header_settings');
    }
};
