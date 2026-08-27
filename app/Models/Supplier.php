<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'posting_rate',
        'tier',
    ];

    public function applications(): HasMany
    {
        return $this->hasMany(CrmApplication::class);
    }

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function auAdds(): HasManyThrough
    {
        return $this->hasManyThrough(AuAdd::class, Card::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function ledgerEntries(): HasMany
    {
        return $this->hasMany(Ledger::class);
    }

    public function cardOffers(): HasMany
    {
        return $this->hasMany(CardOffer::class);
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(Dispute::class);
    }
}