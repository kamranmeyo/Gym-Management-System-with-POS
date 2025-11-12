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
    // Schema::create('sales', function (Blueprint $table) {
    //     $table->id();
    //     $table->foreignId('product_id')->constrained()->onDelete('cascade');
    //     $table->integer('quantity');
    //     $table->decimal('total', 10, 2);
    //     $table->string('payment_method')->default('Cash'); // Cash, JazzCash, etc.
    //     $table->timestamps();
    // });
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->decimal('subtotal', 12, 2);
            $table->decimal('tax', 12, 2)->default(0);
            $table->decimal('total', 12, 2);
            $table->string('payment_method')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
