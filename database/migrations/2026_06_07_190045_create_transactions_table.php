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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('business_id')->constrained();
            $table->foreignId('user_id')->constrained(); // kasir
            $table->foreignId('customer_id')->nullable()->constrained()->nullOnDelete();
            $table->json('items'); // [{name, qty, price}]
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('total');
            $table->enum('pay_method', ['cash', 'transfer'])->default('cash');
            $table->unsignedInteger('cash_received')->nullable();
            $table->unsignedInteger('cash_change')->nullable();
            $table->enum('status', ['lunas', 'belum_lunas'])->default('lunas');
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
