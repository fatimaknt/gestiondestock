<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            // Ajouter les colonnes manquantes si elles n'existent pas
            if (!Schema::hasColumn('stock_movements', 'shop_id')) {
                $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('stock_movements', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
            if (!Schema::hasColumn('stock_movements', 'type')) {
                $table->enum('type', ['in', 'out', 'adjustment', 'transfer']);
            }
            if (!Schema::hasColumn('stock_movements', 'quantity_before')) {
                $table->integer('quantity_before');
            }
            if (!Schema::hasColumn('stock_movements', 'quantity_after')) {
                $table->integer('quantity_after');
            }
            if (!Schema::hasColumn('stock_movements', 'unit_cost')) {
                $table->decimal('unit_cost', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('stock_movements', 'reference')) {
                $table->string('reference')->nullable();
            }
            if (!Schema::hasColumn('stock_movements', 'reference_type')) {
                $table->string('reference_type')->nullable();
            }
            if (!Schema::hasColumn('stock_movements', 'notes')) {
                $table->text('notes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            if (Schema::hasColumn('stock_movements', 'shop_id')) {
                $table->dropForeign(['shop_id']);
                $table->dropColumn('shop_id');
            }
            if (Schema::hasColumn('stock_movements', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            $table->dropColumn([
                'type', 'quantity_before', 'quantity_after', 'unit_cost',
                'reference', 'reference_type', 'notes'
            ]);
        });
    }
};
