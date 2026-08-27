<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}