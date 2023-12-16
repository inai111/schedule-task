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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid();
            $table->timestamp('start_date');
            $table->timestamp('end_date');
            $table->string('down_payment');
            $table->string('total_price');
            $table->enum('order_status',['pending','ongoing','success']);
            $table->enum('payment_status',['pending','ongoing','pay_off']);
            $table->integer('installments_total')->comment('total angsuran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
