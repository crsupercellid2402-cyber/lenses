<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->decimal('rx_sph', 6, 2)->nullable();
            $table->decimal('rx_cyl', 6, 2)->nullable();
            $table->integer('rx_axis')->nullable();
            $table->decimal('rx_add', 6, 2)->nullable();
            $table->decimal('rx_prism', 6, 2)->nullable();
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('rx_sph', 6, 2)->nullable();
            $table->decimal('rx_cyl', 6, 2)->nullable();
            $table->integer('rx_axis')->nullable();
            $table->decimal('rx_add', 6, 2)->nullable();
            $table->decimal('rx_prism', 6, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['rx_sph', 'rx_cyl', 'rx_axis', 'rx_add', 'rx_prism']);
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['rx_sph', 'rx_cyl', 'rx_axis', 'rx_add', 'rx_prism']);
        });
    }
};
