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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('slug');
            $table->enum('status',['waiting','success'])->default('waiting');
            $table->enum('method',['transfer','cash'])->nullable();
            $table->string('total')->nullable();
            $table->string('snap_token')->nullable();

            $table->dateTime('exp_date')->nullable();
            $table->timestamps();
            $table->foreignId('order_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
