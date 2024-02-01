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
            $table->id();
            $table->timestamp('plan_date');
            $table->string('total_price')->default(0);
            $table->enum('order_status',['pending','ongoing','success'])->default('pending');
            $table->integer('invitation')->comment('jumlah undangan')
            ->default(0);
            $table->string('city')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->constrained()
            ->onDelete('cascade');
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
