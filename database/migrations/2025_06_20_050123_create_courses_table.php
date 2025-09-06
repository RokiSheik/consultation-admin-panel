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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image');
            $table->string('video_url')->nullable(); // Preview/Intro video
            $table->decimal('price', 8, 2)->default(0);
            $table->decimal('rating', 3, 2)->default(0);
            $table->integer('total_student')->default(0);
            $table->string('author')->nullable();
            $table->date('date')->nullable();
            $table->json('tags')->nullable();
            $table->json('content')->nullable(); // topic list
            $table->longText('details')->nullable(); // You can store all HTML blocks together
            $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
