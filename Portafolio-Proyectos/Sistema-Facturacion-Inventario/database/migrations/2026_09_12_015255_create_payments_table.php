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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade');
            $table->string('payment_method'); // Efectivo USD, Pago Movil, Zelle, Punto de Venta
            $table->decimal('amount', 15, 2); // Amount paid
            $table->string('currency')->default('VES'); // USD or VES
            $table->decimal('exchange_rate', 15, 4)->default(1); // The exchange rate used for this specific payment
            $table->string('reference_number')->nullable(); // For Zelle/Pago Movil
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
