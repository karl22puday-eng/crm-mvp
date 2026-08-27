<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ledger_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained()->cascadeOnDelete();
            $table->string('request_ref')->nullable(); // e.g. LREQ-0001, set on intake
            $table->date('date_told_us')->nullable();
            $table->string('description')->nullable();
            $table->string('entry_type')->nullable();
            $table->enum('direction', ['we_owe_you', 'you_owe_us'])->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->date('happened_on')->nullable();
            $table->text('supplier_note')->nullable();
            $table->text('our_answer')->nullable();
            $table->string('raised_by')->nullable();
            $table->foreignId('added_as_ledger_id')->nullable()->constrained('ledger')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ledger_requests');
    }
};