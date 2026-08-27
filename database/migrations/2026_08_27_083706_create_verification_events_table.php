<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('verification_events', function (Blueprint $table) {
            $table->id();
            $table->string('vlog_ref')->unique(); // e.g. VLOG-0001
            $table->foreignId('au_add_id')->nullable()->constrained('au_adds')->nullOnDelete();
            $table->foreignId('card_id')->nullable()->constrained()->nullOnDelete();
            $table->date('check_date')->nullable();
            $table->enum('bureau', ['experian', 'equifax', 'transunion'])->nullable();
            $table->enum('result', ['showing', 'not_showing', 'still_waiting'])->nullable();
            $table->text('evidence')->nullable(); // "Where I Saw It"
            $table->string('checked_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('verification_events');
    }
};