<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FinancialProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'annual_revenue',
        'annual_net_profit',
        'avg_monthly_deposits',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(CrmApplication::class, 'application_id');
    }
}