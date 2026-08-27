<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('payment_ref')->unique(); // e.g. PAY-0001
            $table->date('payment_date')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('covers')->nullable(); // which spots/period it covers
            $table->boolean('confirmed')->nullable(); // supplier says "Got It?"
            $table->date('confirmed_date')->nullable();
            $table->boolean('amount_disputed')->default(false);
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};