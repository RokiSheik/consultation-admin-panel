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
        Schema::table('affiliate_requests', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status');
        $table->text('payment_method_details')->nullable()->after('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('affiliate_requests', function (Blueprint $table) {
                    $table->dropColumn(['payment_method', 'payment_method_details']);

        });
    }
};
