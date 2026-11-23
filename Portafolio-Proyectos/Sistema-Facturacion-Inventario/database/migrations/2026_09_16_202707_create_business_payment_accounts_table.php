<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('business_payment_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('method'); // 'pago_movil', 'transfer', 'pos_terminal'
            $table->string('label'); // Ej: "Pago Móvil Banesco"
            $table->string('bank')->nullable();
            $table->string('phone')->nullable();
            $table->string('id_number')->nullable(); // Cédula o RIF
            $table->string('account_number')->nullable();
            $table->string('account_type')->nullable(); // corriente, ahorro
            $table->string('holder_name')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('business_payment_accounts');
    }
};