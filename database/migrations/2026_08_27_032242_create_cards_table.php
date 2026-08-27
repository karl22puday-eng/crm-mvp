<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('card_code')->unique(); // e.g. CARD-0001
            $table->string('issuer_name'); // e.g. Citibank
            $table->decimal('credit_limit', 12, 2);
            $table->date('opened_date');
            $table->unsignedTinyInteger('statement_close_day');
            $table->unsignedTinyInteger('slots_total')->default(0);
            $table->unsignedTinyInteger('spots_sold')->default(0);
            $table->decimal('pay_per_spot', 10, 2)->nullable();
            $table->boolean('still_open')->nullable(); // null = unconfirmed
            $table->decimal('balance', 12, 2)->nullable();
            $table->date('balance_date')->nullable();
            $table->enum('status', ['onboarding', 'active', 'closed'])->default('onboarding');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cards');
    }
};