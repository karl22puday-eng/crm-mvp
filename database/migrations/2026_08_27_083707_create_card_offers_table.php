<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('card_offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('offer_ref')->unique(); // e.g. OFFER-0001
            $table->date('date_submitted')->nullable();
            $table->string('bank')->nullable();
            $table->string('last4', 4)->nullable();
            $table->decimal('credit_limit', 12, 2)->nullable();
            $table->unsignedTinyInteger('slots_offered')->nullable();
            $table->decimal('rate_requested', 10, 2)->nullable();
            $table->enum('status', ['submitted', 'under_review', 'accepted', 'declined', 'more_info_needed'])->default('submitted');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('card_offers');
    }
};