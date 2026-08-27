<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->decimal('annual_revenue', 14, 2)->nullable();
            $table->decimal('annual_net_profit', 14, 2)->nullable();
            $table->decimal('avg_monthly_deposits', 14, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_profiles');
    }
};