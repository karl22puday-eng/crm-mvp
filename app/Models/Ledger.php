<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ledger extends Model
{
    use HasFactory;

    protected $table = 'ledger';

    protected $fillable = [
        'supplier_id', 'item_ref', 'entry_date', 'description', 'type',
        'direction', 'amount', 'agreed', 'settled_date', 'raised_by', 'settlement_ref',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'settled_date' => 'date',
            'agreed' => 'boolean',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}