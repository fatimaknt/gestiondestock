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
        Schema::create('user_shop', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->enum('role', ['owner', 'manager', 'cashier', 'stock_manager'])->default('cashier');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['user_id', 'shop_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_shop');
    }
};
