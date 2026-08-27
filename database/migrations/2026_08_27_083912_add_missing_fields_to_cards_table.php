<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->unsignedTinyInteger('post_window_start_day')->nullable()->after('statement_close_day');
            $table->unsignedTinyInteger('post_window_end_day')->nullable()->after('post_window_start_day');
            $table->unsignedTinyInteger('payment_due_day')->nullable()->after('post_window_end_day');
            $table->string('bureaus_reported')->nullable()->after('status'); // e.g. "EX,EQ,TU"
            $table->decimal('new_credit_limit', 12, 2)->nullable()->after('credit_limit');
        });
    }

    public function down(): void
    {
        Schema::table('cards', function (Blueprint $table) {
            $table->dropColumn(['post_window_start_day', 'post_window_end_day', 'payment_due_day', 'bureaus_reported', 'new_credit_limit']);
        });
    }
};