<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'card_code',
        'issuer_name',
        'credit_limit',
        'new_credit_limit',
        'opened_date',
        'statement_close_day',
        'post_window_start_day',
        'post_window_end_day',
        'payment_due_day',
        'slots_total',
        'spots_sold',
        'pay_per_spot',
        'bureaus_reported',
        'still_open',
        'balance',
        'balance_date',
        'status',
        'notes',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function auAdds(): HasMany
    {
        return $this->hasMany(AuAdd::class);
    }

    public function verificationEvents(): HasMany
    {
        return $this->hasMany(VerificationEvent::class);
    }
}