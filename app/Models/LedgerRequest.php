<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LedgerRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'request_ref', 'date_told_us', 'description', 'entry_type',
        'direction', 'amount', 'happened_on', 'supplier_note', 'our_answer',
        'raised_by', 'added_as_ledger_id',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function ledgerEntry(): BelongsTo
    {
        return $this->belongsTo(Ledger::class, 'added_as_ledger_id');
    }
}