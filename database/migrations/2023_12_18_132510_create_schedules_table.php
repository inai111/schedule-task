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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('date');
            $table->string('location');
            $table->string('note');
            $table->enum('status',['active', 'rejected', 'accepted']);

            $table->timestamps();

            $table->foreignId('order_id')
            ->constrained()->onDelete('cascade');
            $table->foreignId('staf_wo_id')
            ->constrained('users','id')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
