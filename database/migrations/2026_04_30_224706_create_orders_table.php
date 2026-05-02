<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->string('g_number', 50)->unique();
            $table->bigInteger('nm_id'); //есть с отрицательным значением
            $table->string('odid', 50)->default('0');
            $table->bigInteger('income_id')->default(0);

            $table->string('supplier_article', 100)->nullable();
            $table->string('tech_size', 50)->nullable();
            $table->bigInteger('barcode')->nullable(); //есть с отрицательным значением

            $table->decimal('total_price', 12, 2)->default(0);
            $table->smallInteger('discount_percent')->unsigned()->default(0);

            $table->string('warehouse_name', 100)->nullable();
            $table->string('oblast', 100)->nullable();

            $table->string('subject', 100)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('brand', 100)->nullable();

            $table->timestamp('date')->nullable();
            $table->date('last_change_date')->nullable();

            $table->boolean('is_cancel')->default(false);
            $table->timestamp('cancel_dt')->nullable();

            $table->timestamps();

            $table->index('nm_id');
            $table->index('supplier_article');
            $table->index('brand');
            $table->index(['nm_id', 'date']);
            $table->index(['warehouse_name', 'oblast']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
