<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique()->comment('Numero de la factura');
            $table->timestamp('date')->comment('Fecha de la factura');
            $table->foreignId('receiver_id')->comment('id del receptor')->constrained('entities');
            $table->foreignId('transmitter_id')->comment('id del emisor')->constrained('entities');
            $table->decimal('subtotal', 10, 2)->comment('Subtotal de la factura');
            $table->decimal('tax', 10, 2)->comment('Iva');
            $table->decimal('total', 10, 2)->comment('Total de la factura');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
