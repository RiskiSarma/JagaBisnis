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
        Schema::create('businesses', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('type')->default('Retail'); // F&B, Retail, Laundry, Jasa
        $table->enum('status', ['active', 'inactive'])->default('active');
        $table->boolean('feat_stok')->default(false);
        $table->unsignedBigInteger('total_transactions')->default(0);
        $table->unsignedBigInteger('total_revenue')->default(0);
        $table->timestamps();
        $table->softDeletes();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('businesses');
    }
};
