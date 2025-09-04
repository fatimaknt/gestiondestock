<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            // Ajouter les colonnes manquantes si elles n'existent pas
            if (!Schema::hasColumn('sale_items', 'shop_id')) {
                $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            if (Schema::hasColumn('sale_items', 'shop_id')) {
                $table->dropForeign(['shop_id']);
                $table->dropColumn('shop_id');
            }
        });
    }
};
