<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('nm_id');
            $table->bigInteger('barcode')->nullable();

            $table->string('supplier_article', 100)->nullable();
            $table->string('tech_size', 50)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('quantity_full')->nullable();
            $table->string('warehouse_name', 100)->nullable();
            $table->integer('in_way_to_client')->nullable();
            $table->integer('in_way_from_client')->nullable();
            $table->string('subject', 100)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('brand', 100)->nullable();
            $table->string('sc_code', 100)->nullable();
            $table->decimal('price', 12, 2)->nullable();
            $table->decimal('discount', 5, 2)->nullable();
            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();
            $table->boolean('is_supply')->nullable();
            $table->boolean('is_realization')->nullable();
            $table->timestamps();

            $table->index('nm_id');
            $table->index('barcode');
            $table->index('date');
            $table->index('warehouse_name');
            $table->index(['nm_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
