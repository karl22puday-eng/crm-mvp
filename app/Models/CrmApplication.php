<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrmApplication extends Model
{
    use HasFactory;

    protected $table = 'applications';

    protected $fillable = ['supplier_id', 'status'];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function financialProfile(): HasOne
    {
        return $this->hasOne(FinancialProfile::class, 'application_id');
    }

    public function approvalCalculations(): HasMany
    {
        return $this->hasMany(ApprovalCalculation::class, 'application_id');
    }
}