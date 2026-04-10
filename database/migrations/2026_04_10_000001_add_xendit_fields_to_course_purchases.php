<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_purchases', function (Blueprint $table) {
            $table->string('status')->default('paid')->after('amount_paid');
            $table->string('payment_method')->nullable()->after('status');
            $table->string('provider')->nullable()->after('payment_method');
            $table->string('provider_reference')->nullable()->after('provider');
            $table->string('checkout_url')->nullable()->after('provider_reference');
        });
    }

    public function down(): void
    {
        Schema::table('course_purchases', function (Blueprint $table) {
            $table->dropColumn(['status', 'payment_method', 'provider', 'provider_reference', 'checkout_url']);
        });
    }
};
