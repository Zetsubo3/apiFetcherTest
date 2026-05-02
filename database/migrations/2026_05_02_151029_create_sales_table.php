<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            $table->string('g_number', 50)->unique();
            $table->string('sale_id', 50)->nullable();
            $table->bigInteger('nm_id');
            $table->bigInteger('income_id')->default(0);

            $table->string('supplier_article', 100)->nullable();
            $table->string('tech_size', 50)->nullable();
            $table->bigInteger('barcode')->nullable();

            $table->decimal('total_price', 12, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('spp', 8, 2)->nullable();
            $table->decimal('for_pay', 12, 2)->nullable();
            $table->decimal('finished_price', 12, 2)->nullable();
            $table->decimal('price_with_disc', 12, 2)->nullable();

            $table->decimal('promo_code_discount', 12, 2)->nullable();

            $table->string('warehouse_name', 100)->nullable();
            $table->string('country_name', 100)->nullable();
            $table->string('oblast_okrug_name', 100)->nullable();
            $table->string('region_name', 100)->nullable();

            $table->string('subject', 100)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('brand', 100)->nullable();

            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();

            $table->boolean('is_supply')->default(false);
            $table->boolean('is_realization')->default(false);
            $table->boolean('is_storno')->nullable();

            $table->string('odid', 50)->nullable();

            $table->timestamps();

            $table->index('sale_id');
            $table->index('nm_id');
            $table->index('supplier_article');
            $table->index('brand');
            $table->index(['nm_id', 'date']);
            $table->index(['warehouse_name', 'region_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
