<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->default('Sénégal');
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['M', 'F', 'Autre'])->nullable();
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->decimal('total_purchases', 12, 2)->default(0);
            $table->timestamp('last_purchase_date')->nullable();
            $table->timestamps();

            $table->index(['shop_id', 'is_active']);
            $table->index(['shop_id', 'total_purchases']);
            $table->index(['shop_id', 'last_purchase_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
