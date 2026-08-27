<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CardOffer extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'offer_ref', 'date_submitted', 'bank', 'last4',
        'credit_limit', 'slots_offered', 'rate_requested', 'status',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}