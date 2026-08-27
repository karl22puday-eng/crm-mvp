<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_configs', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g. SOP_Bureaus_Required_For_Pay
            $table->string('value'); // stored as string, cast on read
            $table->string('data_type')->default('text'); // integer, decimal, text, date
            $table->boolean('owner_editable')->default(true);
            $table->text('purpose')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_configs');
    }
};