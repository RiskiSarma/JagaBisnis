<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('payment_gateway')->default('manual')->after('payment_method'); // manual | midtrans
            $table->string('midtrans_order_id')->nullable()->unique()->after('payment_gateway');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('snap_token')->nullable()->after('midtrans_transaction_id');
            $table->string('midtrans_payment_type')->nullable()->after('snap_token');
            $table->json('midtrans_raw_response')->nullable()->after('midtrans_payment_type');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway', 'midtrans_order_id', 'midtrans_transaction_id',
                'snap_token', 'midtrans_payment_type', 'midtrans_raw_response',
            ]);
        });
    }
};