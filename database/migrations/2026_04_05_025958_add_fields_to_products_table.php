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
        Schema::table('products', function (Blueprint $table) {
                $table->decimal('price', 12, 2)->default(0);
                $table->decimal('cost_price', 12, 2)->default(0);
                $table->integer('minimum_stock')->default(0);
                $table->string('brand')->nullable();
                $table->decimal('weight', 8, 2)->nullable();
                $table->text('description')->nullable();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
            'price',
            'cost_price',
            'minimum_stock',
            'brand',
            'weight',
            'description',
        ]);
        });
    }
};
