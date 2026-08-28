<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'payment_ref', 'payment_date', 'amount',
        'covers', 'confirmed', 'confirmed_date', 'amount_disputed', 'comment',
    ];

    protected function casts(): array
    {
        return [
            'payment_date' => 'date',
            'confirmed_date' => 'date',
            'confirmed' => 'boolean',
            'amount_disputed' => 'boolean',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}