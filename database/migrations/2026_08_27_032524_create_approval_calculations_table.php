<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approval_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->string('lane'); // e.g. fintech_term_loan, bank_loc
            $table->string('source_metric'); // revenue, net_profit, deposits
            $table->decimal('amount', 12, 2);
            $table->enum('confidence', ['low', 'med', 'high'])->nullable();
            $table->string('basis')->nullable(); // Grounded (band), Inferred, etc.
            $table->string('flag')->nullable(); // Cap, Ceiling, Sub-floor, etc.
            $table->timestamp('ran_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approval_calculations');
    }
};