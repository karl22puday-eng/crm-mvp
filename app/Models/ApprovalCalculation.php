<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApprovalCalculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'application_id',
        'lane',
        'source_metric',
        'amount',
        'confidence',
        'basis',
        'flag',
        'ran_at',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(CrmApplication::class, 'application_id');
    }
}