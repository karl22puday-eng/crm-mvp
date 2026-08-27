<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizing_parameters', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. p_Rev_FTL
            $table->decimal('value', 10, 4);
            $table->string('applies_to')->nullable(); // description
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sizing_parameters');
    }
};