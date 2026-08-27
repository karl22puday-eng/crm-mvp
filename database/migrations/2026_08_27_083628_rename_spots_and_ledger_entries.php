<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::rename('spots', 'au_adds');
        Schema::rename('ledger_entries', 'ledger');
    }

    public function down(): void
    {
        Schema::rename('au_adds', 'spots');
        Schema::rename('ledger', 'ledger_entries');
    }
};