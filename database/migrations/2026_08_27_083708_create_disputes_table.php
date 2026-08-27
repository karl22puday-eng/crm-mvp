<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('disputes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('dispute_ref')->unique(); // e.g. DISP-0001
            $table->date('date_filed')->nullable();
            $table->enum('dispute_type', ['money', 'posting', 'card_details', 'something_else'])->nullable();
            $table->string('subject_ref')->nullable(); // which record it's about
            $table->text('my_claim')->nullable();
            $table->text('my_evidence')->nullable();
            $table->enum('status', ['new', 'under_review', 'resolved_correct', 'resolved_no_change'])->default('new');
            $table->date('resolved_date')->nullable();
            $table->text('our_answer')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('disputes');
    }
};