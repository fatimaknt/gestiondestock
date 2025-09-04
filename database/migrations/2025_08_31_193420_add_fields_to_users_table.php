<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->unique()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('avatar')->nullable()->after('phone');
            $table->enum('role', ['admin', 'vendeur', 'caissier', 'gestionnaire'])->default('vendeur')->after('avatar');
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('set null')->after('role');
            $table->boolean('is_active')->default(true)->after('shop_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropColumn(['username', 'phone', 'avatar', 'role', 'shop_id', 'is_active']);
        });
    }
};
