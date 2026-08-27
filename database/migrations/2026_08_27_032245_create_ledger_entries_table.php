<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('item_ref')->nullable();
            $table->date('entry_date')->nullable();
            $table->string('description')->nullable();
            $table->string('type')->nullable(); // e.g. adjustment, fee, credit
            $table->enum('direction', ['we_owe_you', 'you_owe_us']);
            $table->decimal('amount', 12, 2);
            $table->boolean('agreed')->default(false);
            $table->date('settled_date')->nullable();
            $table->string('raised_by')->nullable();
            $table->string('settlement_ref')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_entries');
    }
};