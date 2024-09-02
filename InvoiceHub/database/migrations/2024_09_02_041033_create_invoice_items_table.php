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
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->comment('Id de la factura')->constrained('invoices');
            $table->foreignId('product_id')->comment('Id del producto')->constrained('products');
            $table->integer('quantity')->comment('Cantidad');
            $table->decimal('unit_price', 10, 2)->comment('Precio unitario');
            $table->decimal('total', 10, 2)->comment('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
