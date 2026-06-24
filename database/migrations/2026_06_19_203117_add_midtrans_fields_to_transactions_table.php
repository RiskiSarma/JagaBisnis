<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->string('payment_method')->default('tunai')->after('total');
            // tunai | transfer | midtrans
            $table->string('payment_gateway')->default('manual')->after('payment_method');
            $table->string('midtrans_order_id')->nullable()->unique()->after('payment_gateway');
            $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
            $table->string('snap_token')->nullable()->after('midtrans_transaction_id');
            $table->string('midtrans_payment_type')->nullable()->after('snap_token');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'payment_method', 'payment_gateway',
                'midtrans_order_id', 'midtrans_transaction_id',
                'snap_token', 'midtrans_payment_type',
            ]);
        });
    }
};