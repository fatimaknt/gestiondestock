<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            if (!Schema::hasColumn('sales', 'shop_id')) {
                $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('sales', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('sales', 'customer_name')) {
                $table->string('customer_name')->nullable();
            }
            if (!Schema::hasColumn('sales', 'customer_phone')) {
                $table->string('customer_phone')->nullable();
            }
            if (!Schema::hasColumn('sales', 'customer_email')) {
                $table->string('customer_email')->nullable();
            }
            if (!Schema::hasColumn('sales', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('sales', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('sales', 'discount_amount')) {
                $table->decimal('discount_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('sales', 'payment_method')) {
                $table->enum('payment_method', ['cash', 'card', 'mobile_money', 'bank_transfer'])->default('cash');
            }
            if (!Schema::hasColumn('sales', 'status')) {
                $table->enum('status', ['pending', 'completed', 'cancelled', 'refunded'])->default('completed');
            }
            if (!Schema::hasColumn('sales', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'shop_id', 'user_id', 'customer_name', 'customer_phone',
                'customer_email', 'subtotal', 'tax_amount', 'discount_amount',
                'payment_method', 'status', 'notes'
            ]);
        });
    }
};
