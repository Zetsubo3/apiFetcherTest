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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('income_id');
            $table->bigInteger('nm_id');
            $table->bigInteger('barcode')->nullable();

            $table->string('number', 100)->nullable();
            $table->string('supplier_article', 100)->nullable();
            $table->string('tech_size', 50)->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('total_price', 12, 2)->default(0);

            $table->string('warehouse_name', 100)->nullable();

            $table->date('date')->nullable();
            $table->date('last_change_date')->nullable();
            $table->date('date_close')->nullable();

            $table->timestamps();

            $table->index('nm_id');
            $table->index('income_id');
            $table->index('barcode');
            $table->index('date');
            $table->index(['nm_id', 'barcode']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
