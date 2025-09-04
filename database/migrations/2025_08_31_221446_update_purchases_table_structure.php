<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Ajouter les colonnes manquantes
            if (!Schema::hasColumn('purchases', 'shop_id')) {
                $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('purchases', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('purchases', 'reference')) {
                $table->string('reference')->unique();
            }
            if (!Schema::hasColumn('purchases', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('purchases', 'tax_amount')) {
                $table->decimal('tax_amount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('purchases', 'status')) {
                $table->enum('status', ['pending', 'ordered', 'received', 'cancelled'])->default('pending');
            }
            if (!Schema::hasColumn('purchases', 'order_date')) {
                $table->date('order_date');
            }
            if (!Schema::hasColumn('purchases', 'expected_delivery')) {
                $table->date('expected_delivery')->nullable();
            }
            if (!Schema::hasColumn('purchases', 'received_date')) {
                $table->date('received_date')->nullable();
            }
            if (!Schema::hasColumn('purchases', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            // Supprimer les colonnes ajoutées
            if (Schema::hasColumn('purchases', 'shop_id')) {
                $table->dropForeign(['shop_id']);
                $table->dropColumn('shop_id');
            }
            if (Schema::hasColumn('purchases', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            $table->dropColumn([
                'reference', 'subtotal', 'tax_amount', 'status',
                'order_date', 'expected_delivery', 'received_date', 'notes'
            ]);
        });
    }
};
