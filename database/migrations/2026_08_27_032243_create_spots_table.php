<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained()->cascadeOnDelete();
            $table->string('add_ref')->unique(); // e.g. ADD-0001
            $table->string('client_name');
            $table->string('order_ref')->nullable();
            $table->date('date_added');
            $table->boolean('experian_showing')->nullable();
            $table->boolean('equifax_showing')->nullable();
            $table->boolean('transunion_showing')->nullable();
            $table->date('date_checked')->nullable();
            $table->string('source')->nullable(); // "Where I Saw It"
            $table->boolean('minimum_met')->default(false); // computed: 2+ bureaus showing
            $table->decimal('rate', 10, 2)->nullable();
            $table->enum('payout_status', ['pending', 'held', 'due', 'paid'])->default('pending');
            $table->decimal('payout_amount', 10, 2)->default(0);
            $table->boolean('paid')->default(false);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spots');
    }
};