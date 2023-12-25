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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->string('total_price');
            $table->string('note');
            $table->enum('status',['active', 'rejected', 'accepted'])->default('active');

            $table->timestamps();
            $table->softDeletes();
            
            $table->foreignId('vendor_id')
            ->constrained()->onDelete('cascade');
            $table->foreignId('schedule_id')->nullable()
            ->constrained()->onDelete('cascade');
            $table->foreignId('order_id')
            ->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
