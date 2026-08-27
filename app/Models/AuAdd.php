<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AuAdd extends Model
{
    use HasFactory;

    protected $table = 'au_adds';

    protected $fillable = [
        'card_id', 'add_ref', 'client_name', 'order_ref', 'date_added',
        'experian_showing', 'equifax_showing', 'transunion_showing', 'date_checked',
        'source', 'minimum_met', 'rate', 'payout_status', 'payout_amount', 'paid', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'date_added' => 'date',
            'date_checked' => 'date',
            'experian_showing' => 'boolean',
            'equifax_showing' => 'boolean',
            'transunion_showing' => 'boolean',
            'minimum_met' => 'boolean',
            'paid' => 'boolean',
        ];
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }

    public function verificationEvents(): HasMany
    {
        return $this->hasMany(VerificationEvent::class, 'au_add_id');
    }
}