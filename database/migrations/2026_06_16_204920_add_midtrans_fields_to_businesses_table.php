<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->string('midtrans_merchant_id')->nullable()->after('paket');
            $table->text('midtrans_server_key')->nullable()->after('midtrans_merchant_id');   // dienkripsi
            $table->text('midtrans_client_key')->nullable()->after('midtrans_server_key');     // dienkripsi
            $table->boolean('midtrans_is_production')->default(false)->after('midtrans_client_key');
            $table->boolean('midtrans_is_active')->default(false)->after('midtrans_is_production');
            $table->timestamp('midtrans_connected_at')->nullable()->after('midtrans_is_active');
        });
    }

    public function down(): void
    {
        Schema::table('businesses', function (Blueprint $table) {
            $table->dropColumn([
                'midtrans_merchant_id', 'midtrans_server_key', 'midtrans_client_key',
                'midtrans_is_production', 'midtrans_is_active', 'midtrans_connected_at',
            ]);
        });
    }
};