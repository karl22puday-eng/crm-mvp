<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'vlog_ref', 'au_add_id', 'card_id', 'check_date', 'bureau',
        'result', 'evidence', 'checked_by', 'notes',
    ];

    public function auAdd(): BelongsTo
    {
        return $this->belongsTo(AuAdd::class, 'au_add_id');
    }

    public function card(): BelongsTo
    {
        return $this->belongsTo(Card::class);
    }
}